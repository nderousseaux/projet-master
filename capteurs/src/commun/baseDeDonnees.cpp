#include <libssh2.h>
#include <libssh2_sftp.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <unistd.h>
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
		}
	}

	// Ferme la base de données agrégée
	sqlite3_close(db);

	return EXIT_SUCCESS;
}

int sendDB(const char* ip_address)
{
    // TODO : mettre le bon chemin !
    const char path[] = "/db";

    int err = libssh2_init(0);
    if (err < 0)
    {
        std::cerr << "Failed to initialize libssh2." << std::endl;
        return err;
    }

    LIBSSH2_SESSION *session = libssh2_session_init();
    if (!session) {
        std::cerr << "Failed to create session." << std::endl;
        return -1;
    }

    int sockfd = socket(AF_INET, SOCK_STREAM, 0);

    struct sockaddr_in addr;
    addr.sin_family = AF_INET;
    addr.sin_port = htons(22);
    addr.sin_addr.s_addr = inet_addr(ip_address);

    if (connect(sockfd, (struct sockaddr*)&addr, sizeof(addr)) < 0)
    {
        std::cerr << "Failed to connect socket." << std::endl;
        return err;
    }

    err = libssh2_session_startup(session, sockfd);
    if (err)
    {
        std::cerr << "Failed to start session up." << std::endl;
        return err;
    }

    LIBSSH2_SFTP *sftp = libssh2_sftp_init(session);
    if (!sftp)
    {
        std::cerr << "Failed to initialize SFTP session." << std::endl;
        return -1;
    }

    LIBSSH2_SFTP_HANDLE *handle = libssh2_sftp_open(sftp, path, LIBSSH2_FXF_WRITE, 0);
    if (!handle)
    {
        std::cerr << "Failed to open remote file." << std::endl;
        return -1;
    }

//    libssh2_sftp_write(handle, );

    libssh2_sftp_shutdown(sftp);
    libssh2_session_disconnect(session, "Normal Shutdown");
    libssh2_session_free(session);
    libssh2_exit();
    close(sockfd);

    return 0;
}