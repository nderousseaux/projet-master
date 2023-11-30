#include "baseDeDonnees.hpp"

/* Public */

int BaseDeDonnees::ouvrirBaseDeDonnees(std::string nomBaseDeDonnees) {
	sqlite3* db;
	int retour = 0;
	char* msgErr = 0;

	// Création du dossier de la base de données, si il n'existe pas
	struct stat st = {0};
	if (stat(BaseDeDonnees::DOSSIER_BDD.c_str(), &st) == -1) {
		mkdir(BaseDeDonnees::DOSSIER_BDD.c_str(), 0700);
	}

	// Ouverture de la base de données
	retour = sqlite3_open((BaseDeDonnees::DOSSIER_BDD + nomBaseDeDonnees)
		.c_str(), &db);

	// Vérifie le code de retour
	if (retour != SQLITE_OK) {
		std::cerr << "Impossible d'ouvrir la base de données : " <<
			sqlite3_errmsg(db) << std::endl;
		sqlite3_close(db);
		return EXIT_FAILURE;
	}

	// Création de la table, si elle n'existe pas
	const char* requete = "CREATE TABLE IF NOT EXISTS mesures"
						"(date TEXT, idChamp INTEGER, idIlot INTEGER,"
						"idCapt INTEGER, temp REAL, humi REAL, lumi REAL)";

	retour = sqlite3_exec(db, requete, 0, 0, &msgErr);

	// Vérifie le code de retour
	if (retour != SQLITE_OK) {
		std::cerr << "Erreur à l'exécution de la requête : " << msgErr <<
			std::endl;
		sqlite3_free(msgErr);
		sqlite3_close(db);
		return EXIT_FAILURE;
	}

	this->setDbHandler(db);
	return EXIT_SUCCESS;
}

int BaseDeDonnees::nettoyerBaseDeDonnees(void) {
	// Récupère le handler de la base de données
	sqlite3* db = this->getDbHandler();

	char* msgErr = 0;

	// Préparation de la requête SQL
	sqlite3_stmt* stmt;
	const char* requete = "DELETE FROM mesures";
	int retour = sqlite3_prepare_v2(db, requete, -1, &stmt, NULL);

	// Vérifie le code de retour
	if (retour != SQLITE_OK) {
		std::cerr << "Erreur à la préparation de la requête : " <<
			sqlite3_errmsg(db) << std::endl;
		sqlite3_close(db);
		return EXIT_FAILURE;
	}

	// Exécution de la requête
	retour = sqlite3_step(stmt);

	// Vérifie le code de retour
	if (retour != SQLITE_DONE) {
		std::cerr << "Erreur à l'exécution de la requête : " <<
			sqlite3_errmsg(db) << std::endl;
		sqlite3_free(msgErr);
		sqlite3_close(db);
		return EXIT_FAILURE;
	}

	sqlite3_finalize(stmt);
	return EXIT_SUCCESS;
}

int BaseDeDonnees::insererMesures(InfosChamp infosChamp, Mesures mesures) {
	// Récupère le handler de la base de données
	sqlite3* db = this->getDbHandler();

	char* msgErr = 0;

	// Préparation de la requête SQL
	sqlite3_stmt* stmt;
	const char* requete = "INSERT INTO mesures"
							"(date, idChamp, idIlot, idCapt, temp, humi, lumi) "
							"VALUES (?, ?, ?, ?, ?, ?, ?)";
	int retour = sqlite3_prepare_v2(db, requete, -1, &stmt, NULL);

	// Vérifie le code de retour
	if (retour != SQLITE_OK) {
		std::cerr << "Erreur à la préparation de la requête : " <<
			sqlite3_errmsg(db) << std::endl;
		sqlite3_close(db);
		return EXIT_FAILURE;
	}

	// Récupère la date et l'heure
	time_t t = time(NULL);
	struct tm tm = *gmtime(&t);
	std::ostringstream oss;
	oss << std::put_time(&tm, "%Y-%m-%d %H:%M:%S");
	std::string date = oss.str();

	// Bind des paramètres
	sqlite3_bind_text(stmt, 1, date.c_str(), -1, SQLITE_TRANSIENT);
	sqlite3_bind_int(stmt, 2, infosChamp.getIdChamp());
	sqlite3_bind_int(stmt, 3, infosChamp.getIdIlot());
	sqlite3_bind_int(stmt, 4, infosChamp.getIdCapt());
	sqlite3_bind_double(stmt, 5, mesures.getTemperature());
	sqlite3_bind_double(stmt, 6, mesures.getHumidite());
	sqlite3_bind_int(stmt, 7, mesures.getLuminosite());

	// Exécution de la requête
	retour = sqlite3_step(stmt);

	// Vérifie le code de retour
	if (retour != SQLITE_DONE) {
		std::cerr << "Erreur à l'exécution de la requête : " <<
			sqlite3_errmsg(db) << std::endl;
		sqlite3_free(msgErr);
		sqlite3_close(db);
		return EXIT_FAILURE;
	}

	sqlite3_finalize(stmt);
	return EXIT_SUCCESS;
}

int BaseDeDonnees::fermerBaseDeDonnees(void) {
	// Récupère le handler de la base de données
	sqlite3* db = this->getDbHandler();

	// Fermeture de la base de données
	sqlite3_close(db);
	return EXIT_SUCCESS;
}

int BaseDeDonnees::agregerMesures(void) {
	// Ouvre la base de données principale
	sqlite3* db = this->getDbHandler();

	char* msgErr = 0;

	// Récupère le nom de la base de données
	const char* nomComplet = sqlite3_db_filename(db, "main");
	std::string cheminComplet(nomComplet);
	std::string fichierBdd = std::filesystem::path(cheminComplet).filename()
		.string();

	// Parcourt les fichiers dans le dossier des bases de données
	for (
		const auto& entry :
		std::filesystem::directory_iterator(BaseDeDonnees::DOSSIER_BDD)
	) {
		// Vérifie que le fichier est une base de données SQLite
		if (
			entry.is_regular_file() &&
			entry.path().extension().string() == ".db" &&
			entry.path().filename() != fichierBdd
		) {
			// Attache la base de données à la base principale
			std::string requete = "attach '" + entry.path().string() +
				"' as toMerge;";
			int retour = sqlite3_exec(db, requete.c_str(), NULL, 0, &msgErr);

			if (retour != SQLITE_OK) {
				std::cerr << "Erreur à l'attachement de la base de données : "
					<< msgErr << std::endl;
				sqlite3_free(msgErr);
				sqlite3_close(db);
				return EXIT_FAILURE;
			}

			// Démarre une transaction
			requete = "BEGIN;";
			retour = sqlite3_exec(db, requete.c_str(), NULL, 0, &msgErr);

			if (retour != SQLITE_OK) {
				std::cerr << "Erreur au début de la transaction : " << msgErr
					<< std::endl;
				sqlite3_free(msgErr);
				sqlite3_close(db);
				return EXIT_FAILURE;
			}

			// Préparation de la requête SQL
			sqlite3_stmt* stmt;
			const char* insertQuery = "INSERT INTO mesures SELECT * FROM "
				"toMerge.mesures;";
			retour = sqlite3_prepare_v2(db, insertQuery, -1, &stmt, NULL);

			if (retour != SQLITE_OK) {
				std::cerr << "Erreur à la préparation de la requête : " <<
					sqlite3_errmsg(db) << std::endl;
				sqlite3_close(db);
				return EXIT_FAILURE;
			}

			// Exécution de la requête
			retour = sqlite3_step(stmt);

			if (retour != SQLITE_DONE) {
				std::cerr << "Erreur à l'insertion des données : " <<
					sqlite3_errmsg(db) << std::endl;
				sqlite3_close(db);
				return EXIT_FAILURE;
			}

			sqlite3_finalize(stmt);

			// Valide la transaction
			requete = "COMMIT;";
			retour = sqlite3_exec(db, requete.c_str(), NULL, 0, &msgErr);

			if (retour != SQLITE_OK) {
				std::cerr << "Erreur à la validation de la transaction : " <<
					msgErr << std::endl;
				sqlite3_free(msgErr);
				sqlite3_close(db);
				return EXIT_FAILURE;
			}

			// Détache la base attachée
			requete = "detach toMerge;";
			retour = sqlite3_exec(db, requete.c_str(), NULL, 0, &msgErr);

			if (retour != SQLITE_OK) {
				std::cerr << "Erreur au détachement de la base de données : " <<
					msgErr << std::endl;
				sqlite3_free(msgErr);
				sqlite3_close(db);
				return EXIT_FAILURE;
			}

			// Supprime la base de données attachée
			std::filesystem::remove(entry.path());
		}
	}

	// Ferme la base de données agrégée
	sqlite3_close(db);

	return EXIT_SUCCESS;
}

int BaseDeDonnees::conversionDBversChar(char *buffer) {
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

int BaseDeDonnees::envoiBDD() {
	// Récupère les identifiants
	std::vector<std::string> contenu = recupereIdentifiants(BaseDeDonnees::CHEMIN_FICHIER_IDENTIFIANTS);

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

	int sockfd = socket(AF_INET, SOCK_STREAM, 0);

	struct sockaddr_in adresse;
	adresse.sin_family = AF_INET;
	adresse.sin_port = htons(22);
	adresse.sin_addr.s_addr = inet_addr("185.155.93.77");

	if (connect(sockfd, (struct sockaddr*)&adresse, sizeof(adresse)) < 0) {
		std::cerr << "Echec lors de la connection de la socket." << std::endl;
		return err;
	}

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
	if (conversionDBversChar(base_donnees) < 0)
	{
		// TODO handle error
	}
	libssh2_sftp_write(agent, base_donnees, strlen(base_donnees));

	libssh2_sftp_shutdown(sftp);
	libssh2_session_disconnect(session, "Exctinction normale");
	libssh2_session_free(session);
	libssh2_exit();
	close(sockfd);

	return 0;
}

std::vector<std::string> BaseDeDonnees::recupereIdentifiants(
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