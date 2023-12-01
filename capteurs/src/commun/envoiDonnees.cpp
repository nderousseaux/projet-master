#include "envoiDonnees.hpp"

/* Public */

std::vector<std::string> EnvoiDonnees::recupereIdentifiants(
	const std::string cheminFichier
) {
	std::vector<std::string> retour;
	std::ifstream fichier(cheminFichier);

	if (!fichier) {
		throw std::runtime_error("Le fichier n'existe pas");
	}

	if (fichier.is_open()) {
		std::string ligne;
		while (std::getline(fichier, ligne)) {
			retour.push_back(ligne);
		}
		fichier.close();
	}

	return retour;
}

int EnvoiDonnees::conversionBDDversChar(char *buffer) {
	const char* requete_totale = "SELECT * FROM db;";

	sqlite3_stmt* stmt;
	if (sqlite3_prepare_v2(db_, requete_totale, -1, &stmt, NULL) != SQLITE_OK) {
		return -1;
	}

	// Loop through the results
	int num_colonne = 0, index_buffer = 0;
	while (sqlite3_step(stmt) == SQLITE_ROW) {
		const char* donnees_colonne = (const char*)sqlite3_column_text(stmt,
			num_colonne);

		int longueur = strlen(donnees_colonne);
		snprintf(buffer + index_buffer, longueur, "%s\n", donnees_colonne);
		index_buffer += longueur;

		num_colonne++;
	}

	sqlite3_finalize(stmt);

	return 0;
}

int EnvoiDonnees::initialisation_socket(const int &port, const char *ip) {

    int sockfd = socket(AF_INET, SOCK_STREAM, 0);

    struct sockaddr_in adresse;
    adresse.sin_family = AF_INET;
    adresse.sin_port = htons(22);
    adresse.sin_addr.s_addr = inet_addr(ip);

    if (connect(sockfd, (struct sockaddr*)&adresse, sizeof(adresse)) < 0) {
        std::cerr << "Echec lors de la connection de la socket." << std::endl;
        return -1;
    }

    return sockfd;
}

int EnvoiDonnees::envoiBDD() {
	// Récupère les identifiants
	std::vector<std::string> contenu = recupereIdentifiants(
		EnvoiDonnees::CHEMIN_FICHIER_IDENTIFIANTS);

	/*
	 * Vérifie que le fichier est mal formé (3 lignes : IP, identifiant, mot de
	 * passe)
	 */
	if (contenu.size() != 3) {
		std::cerr << "Le fichier d'identifiants est mal formé" << std::endl;
		return -1;
	}

	std::string ip = contenu[0];
	std::string identifiant = contenu[1];
	std::string mdp = contenu[2];

	// TODO : mettre le bon chemin !
	const char chemin_distant_vers_BDD[] = "/.bdd";

	int err = libssh2_init(0);
	if (err < 0) {
		std::cerr << "Echec lors de l'initialisation de libssh2." << std::endl;
		return err;
	}

	LIBSSH2_SESSION *session = libssh2_session_init();
	if (!session) {
		char *tampon_msg_erreur;
		int longeur_erreur;
		libssh2_session_last_error(session, &tampon_msg_erreur, &longeur_erreur,
			0);
		std::cerr << "Echec lors de la création de la session.\nErreur : " <<
			tampon_msg_erreur << std::endl;
		return -1;
	}

    int sockfd = initialisation_socket(22, ip.c_str());
    if (sockfd < 0)
        return sockfd;

	err = libssh2_session_startup(session, sockfd);
	if (err) {
		char *tampon_msg_erreur;
		int longeur_erreur;
		libssh2_session_last_error(session, &tampon_msg_erreur, &longeur_erreur,
			0);
		std::cerr << "Echec lors du démarage de la session.\nErreur : " <<
			tampon_msg_erreur << std::endl;
		return err;
	}

    err = libssh2_userauth_password(session, identifiant.c_str(), mdp.c_str());
    if (err) {
        char *tampon_msg_erreur;
        int longeur_erreur;
        libssh2_session_last_error(session, &tampon_msg_erreur, &longeur_erreur, 0);
        std::cerr << "Echec lors de l'authenification." << std::endl << "Erreur : " << tampon_msg_erreur << std::endl;

        libssh2_session_disconnect(session, "Normal Shutdown");
        libssh2_session_free(session);
        libssh2_exit();
        return err;
    }

	LIBSSH2_SFTP *sftp = libssh2_sftp_init(session);
	if (!sftp) {
		char *tampon_msg_erreur;
		int longeur_erreur;
		libssh2_session_last_error(session, &tampon_msg_erreur, &longeur_erreur,
			0);
		std::cerr << "Echec lors de l'initialisation de la session SFTP." <<
			std::endl << "Erreur : " << tampon_msg_erreur << std::endl;
		return -1;
	}

	LIBSSH2_SFTP_HANDLE *agent = libssh2_sftp_open(sftp,
		chemin_distant_vers_BDD, LIBSSH2_FXF_WRITE, 0);
	if (!agent) {
		char *tampon_msg_erreur;
		int longeur_erreur;
		libssh2_session_last_error(session, &tampon_msg_erreur, &longeur_erreur,
			0);
		std::cerr << "Echec lors de l'ouverture du fichier distant." <<
			std::endl << "Erreur : " << tampon_msg_erreur << std::endl;
		return -1;
	}

	// libssh2_sftp_write(agent, );
	char base_donnees[1024*1024];
	if (conversionBDDversChar(base_donnees) < 0)
	{
		// TODO handle error
	}
	libssh2_sftp_write(agent, base_donnees, strlen(base_donnees));

	libssh2_sftp_shutdown(sftp);
	libssh2_session_disconnect(session, "Exctinction normale");
	libssh2_session_free(session);
	libssh2_exit();
	close(sockfd);

	// Supprime le fichier de données, si l'envoi s'est bien passé
	std::remove(StockageDonnees::CHEMIN_FICHIER_AGREGE.c_str());

	return 0;
}