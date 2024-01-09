#include "envoiDonnees.hpp"

/* Public */

std::vector<std::string> EnvoiDonnees::recupereIdentifiants(
	const std::string cheminFichier
) {
	std::vector<std::string> retour;
	std::ifstream fichier(cheminFichier);

	// Vérifie que le fichier existe
	if (!fichier) {
		throw std::runtime_error("Le fichier \"" + cheminFichier + "\" " +
			"n'existe pas");
	}

	// Récupère les identifiants
	if (fichier.is_open()) {
		std::string ligne;
		while (std::getline(fichier, ligne)) {
			retour.push_back(ligne);
		}
		fichier.close();
	}

	return retour;
}

void EnvoiDonnees::afficheMsgErrLibssh2(
	LIBSSH2_SESSION* session,
	const std::string& msgErreur
) {
	char* tamponMsgErreur;
	int longeurErreur;
	libssh2_session_last_error(session, &tamponMsgErreur, &longeurErreur, 0);
	std::cerr << msgErreur << "\nErreur : " << tamponMsgErreur << std::endl;
}

int EnvoiDonnees::initialisationSocket(const int& port, const char* ip) {
	int sockfd = socket(AF_INET, SOCK_STREAM, 0);

	struct sockaddr_in adresse;
	adresse.sin_family = AF_INET;
	adresse.sin_port = htons(port);
	adresse.sin_addr.s_addr = inet_addr(ip);

	if (connect(sockfd, (struct sockaddr*)&adresse, sizeof(adresse)) < 0) {
		std::cerr << "Echec lors de la connexion de la socket" << std::endl;
		return -1;
	}

	return sockfd;
}

int EnvoiDonnees::envoiFichierSFTP(std::string cheminFichier) {
	// Récupère le nom du fichier
	std::string nomFichier = cheminFichier.substr(
		cheminFichier.find_last_of('/') + 1);
	std::string cheminDistantServ = EnvoiDonnees::DOSSIER_DISTANT + nomFichier;

	// Récupère les identifiants
	std::vector<std::string> contenu = recupereIdentifiants(
		EnvoiDonnees::CHEMIN_FICHIER_IDENTIFIANTS);

	/*
	 * Vérifie que le fichier n'est pas mal formé
	 * (4 lignes : IP, port, identifiant et mot de passe)
	 */
	if (contenu.size() != 4) {
		std::cerr << "Le fichier d'identifiants est mal formé" << std::endl;
		return -1;
	}
	std::string ip = contenu[0];
	const int port = std::stoi(contenu[1]);
	std::string identifiant = contenu[2];
	std::string mdp = contenu[3];

	// Initialise la session
	int err = libssh2_init(0);
	if (err < 0) {
		std::cerr << "Echec lors de l'initialisation de libssh2" << std::endl;
		return err;
	}

	LIBSSH2_SESSION* session = libssh2_session_init();
	if (!session) {
		afficheMsgErrLibssh2(session,
			"Echec lors de la création de la session");
		return -1;
	}

	// Initialise la socket
	int sockfd = initialisationSocket(port, ip.c_str());
	if (sockfd < 0)
		return sockfd;

	err = libssh2_session_startup(session, sockfd);
	if (err) {
		afficheMsgErrLibssh2(session,
			"Echec lors du démarage de la session");
		return err;
	}

	err = libssh2_userauth_password(session, identifiant.c_str(), mdp.c_str());
	if (err) {
		afficheMsgErrLibssh2(session, "Echec lors de l'authenification");
		libssh2_session_disconnect(session, "Extinction normale");
		libssh2_session_free(session);
		libssh2_exit();
		return err;
	}

	LIBSSH2_SFTP* sftp = libssh2_sftp_init(session);
	if (!sftp) {
		afficheMsgErrLibssh2(session,
			"Echec lors de l'initialisation de la session SFTP");
		return -1;
	}

	LIBSSH2_SFTP_HANDLE* agent = libssh2_sftp_open(sftp,
		cheminDistantServ.c_str(), LIBSSH2_FXF_CREAT, 0);

	if (!agent) {
		afficheMsgErrLibssh2(session,
			"Echec lors de l'ouverture du fichier distant");
		return -1;
	}

	// Rempli le tampon avec le contenu du fichier
	std::ifstream fichier(cheminFichier);
	std::string tampon((std::istreambuf_iterator<char>(fichier)), 
		std::istreambuf_iterator<char>());

	// Envoie le tampon vers le serveur SFTP
	libssh2_sftp_write(agent, tampon.c_str(), strlen(tampon.c_str()));

	// Ferme les connexions
	libssh2_sftp_shutdown(sftp);
	libssh2_session_disconnect(session, "Extinction normale");
	libssh2_session_free(session);
	libssh2_exit();
	close(sockfd);

	// Supprime le fichier de données, si l'envoi s'est bien passé
	std::remove(cheminFichier.c_str());

	return 0;
}