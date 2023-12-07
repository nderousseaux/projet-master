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


void EnvoiDonnees::affiche_message_erreur_libssh2(LIBSSH2_SESSION* session,
					const std::string& msg_erreur) {

	char *tampon_msg_erreur;
	int longeur_erreur;
	libssh2_session_last_error(session, &tampon_msg_erreur, &longeur_erreur,
							   0);
	std::cerr << msg_erreur << "\nErreur : " << tampon_msg_erreur << std::endl;
}

//int EnvoiDonnees::conversionBDDversChar(char *buffer) {
//	const char* requete_totale = "SELECT * FROM db;";
//
//	sqlite3_stmt* stmt;
//	if (sqlite3_prepare_v2(db_, requete_totale, -1, &stmt, NULL) != SQLITE_OK) {
//		return -1;
//	}
//
//	int num_colonne = 0, index_buffer = 0;
//	while (sqlite3_step(stmt) == SQLITE_ROW) {
//		const char* donnees_colonne = (const char*)sqlite3_column_text(stmt,
//			num_colonne);
//
//		int longueur = strlen(donnees_colonne);
//		snprintf(buffer + index_buffer, longueur, "%s\n", donnees_colonne);
//		index_buffer += longueur;
//
//		num_colonne++;
//	}
//
//	sqlite3_finalize(stmt);
//
//	return 0;
//}

int EnvoiDonnees::ecrireBDD(LIBSSH2_SFTP_HANDLE *agent)
{
	char b[1];
	int descripteur_de_fichier = open("donnee.txt", O_RDONLY);
	int lu;

	do {
		lu = read(descripteur_de_fichier, b, 1);
		if (lu == 1)
			libssh2_sftp_write(agent, b, 1);
		std::cout << b << std::flush;
	} while (lu == 1);

	std::cout << std::endl;
	return lu;
}

int EnvoiDonnees::initialisation_socket(const int &port, const char *ip) {

	int sockfd = socket(AF_INET, SOCK_STREAM, 0);

	struct sockaddr_in adresse;
	adresse.sin_family = AF_INET;
	adresse.sin_port = htons(port);
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
		affiche_message_erreur_libssh2(session,
			"Echec lors de la création de la session.");
		return -1;
	}

	int sockfd = initialisation_socket(22, ip.c_str());
	if (sockfd < 0)
		return sockfd;

	err = libssh2_session_startup(session, sockfd);
	if (err) {
		affiche_message_erreur_libssh2(session,
			"Echec lors du démarage de la session.");
		return err;
	}

	err = libssh2_userauth_password(session, identifiant.c_str(), mdp.c_str());
	if (err) {
		affiche_message_erreur_libssh2(session, "Echec lors de l'authenification.");

		libssh2_session_disconnect(session, "Normal Shutdown");
		libssh2_session_free(session);
		libssh2_exit();
		return err;
	}

	LIBSSH2_SFTP *sftp = libssh2_sftp_init(session);
	if (!sftp) {
		affiche_message_erreur_libssh2(session,
			"Echec lors de l'initialisation de la session SFTP.");
		return -1;
	}

	LIBSSH2_SFTP_HANDLE *agent = libssh2_sftp_open(sftp,
		chemin_distant_vers_BDD, LIBSSH2_FXF_WRITE, 0);
	if (!agent) {

		affiche_message_erreur_libssh2(session,
			"Echec lors de l'ouverture du fichier distant.");

		return -1;
	}

	if (ecrireBDD(agent) < 0)
	{
		// TODO handle error
		std::cout << "Erreur lors de la conversion du fichier BDD" << std::endl;
		return -1;
	}

	libssh2_sftp_shutdown(sftp);
	libssh2_session_disconnect(session, "Extinction normale");
	libssh2_session_free(session);
	libssh2_exit();
	close(sockfd);

	// Supprime le fichier de données, si l'envoi s'est bien passé
	std::remove(StockageDonnees::CHEMIN_FICHIER_AGREGE.c_str());

	return 0;
}