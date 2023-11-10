#include "baseDeDonnees.hpp"

/* Public */

int BaseDeDonnees::ouvrirBaseDeDonnees(void) {
	sqlite3* db;
	int retour = 0;
	char* msgErr = 0;

	// Création du dossier de la base de données, si il n'existe pas
	struct stat st = {0};
	if (stat(BaseDeDonnees::DOSSIER_BDD.c_str(), &st) == -1) {
		mkdir(BaseDeDonnees::DOSSIER_BDD.c_str(), 0700);
	}

	// Ouverture de la base de données
	retour = sqlite3_open(
		(BaseDeDonnees::DOSSIER_BDD + BaseDeDonnees::FICHIER_BDD).c_str(),
		&db
	);

	// Vérifie le code de retour
	if (retour != SQLITE_OK) {
		fprintf(stderr, "Impossible d'ouvrir la base de données: %s\n",
			sqlite3_errmsg(db));
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
		fprintf(stderr, "Erreur à l'exécution de la requête : %s\n", msgErr);
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
		fprintf(stderr, "Erreur à la préparation de la requête : %s\n",
			sqlite3_errmsg(db));
		sqlite3_close(db);
		return EXIT_FAILURE;
	}

	// Exécution de la requête
	retour = sqlite3_step(stmt);

	// Vérifie le code de retour
	if (retour != SQLITE_DONE) {
		fprintf(stderr, "Erreur à l'exécution de la requête : %s\n",
			sqlite3_errmsg(db));
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
		fprintf(stderr, "Erreur à la préparation de la requête : %s\n",
			sqlite3_errmsg(db));
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
		fprintf(stderr, "Erreur à l'exécution de la requête : %s\n",
			sqlite3_errmsg(db));
		sqlite3_free(msgErr);
		sqlite3_close(db);
		return EXIT_FAILURE;
	}

	sqlite3_finalize(stmt);
	return EXIT_SUCCESS;
}