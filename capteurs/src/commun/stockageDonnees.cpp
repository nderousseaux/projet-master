#include "stockageDonnees.hpp"

/* Public */

int StockageDonnees::ecrireDonneesFichier(
	const std::string& nomFichier,
	InfosChamp infosChamp,
	Mesures mesures
) {
	// Créé le dossier s'il n'existe pas
	std::filesystem::create_directory(DOSSIER_STOCKAGE);

	// Ecrit les données dans le fichier
	std::ofstream file(DOSSIER_STOCKAGE + nomFichier, std::ios::app);
	if (file.is_open()) {
		file <<
			mesures.getDate() << ';' <<
			infosChamp.getIdChamp() << ';' <<
			infosChamp.getIdIlot() << ';' <<
			infosChamp.getIdCapteur() << ';' <<
			mesures.getTemperature() << ';' <<
			mesures.getHumidite() << ';' <<
			mesures.getLuminosite() <<
			std::endl;
		file.close();
	}
	else {
		std::cerr << "Impossible d'ouvrir le fichier : \"" << nomFichier << "\""
			<< std::endl;
		return EXIT_FAILURE;
	}
	return EXIT_SUCCESS;
}

std::string StockageDonnees::dateUTCActuelle() {
	// Récupère la date actuelle
	auto dateActuelle = std::chrono::system_clock::now();

	// Converti en temps UTC
	std::time_t dateActuelleUTC = std::chrono::system_clock::to_time_t(
		dateActuelle);

	// Formate la date en string
	char buffer[20];
	std::strftime(buffer, sizeof(buffer), "%Y-%m-%d %H:%M:%S",
		std::gmtime(&dateActuelleUTC));

	return buffer;
}

void StockageDonnees::agregeFichiersDonnees() {
	// Ouvre le fichier de destination
	std::string cheminFichier = StockageDonnees::CHEMIN_FICHIER_AGREGE;

	std::ofstream fichierAgrege(cheminFichier, std::ios::out | std::ios::app);

	// Vérifie si le fichier est ouvert
	if (fichierAgrege.is_open()) {
		// Parcourt tous les fichiers dans le dossier "stockage"
		for (
			const auto& fichier :
			std::filesystem::directory_iterator(
				StockageDonnees::DOSSIER_STOCKAGE
			)
		) {
			// Vérifie si le fichier est le fichier de destination
			if (fichier.path().string() == cheminFichier) {
				continue;
			}

			// Vérifie si le fichier est un fichier texte
			if (fichier.path().extension() == ".txt") {
				// Ouvre le fichier source
				std::ifstream fichierSource(fichier.path().string(),
					std::ios::in);

				// Vérifie si le fichier source est ouvert
				if (fichierSource.is_open()) {
					/*
					 * Lit le contenu du fichier source et l'écrit dans le
					 * fichier de destination
					 */
					fichierAgrege << fichierSource.rdbuf();

					// Ferme le fichier source
					fichierSource.close();

					// Supprime le fichier source
					std::filesystem::remove(fichier.path());
				}
				else {
					std::cerr << "Impossible d'ouvrir le fichier source : \"" <<
						fichier.path().string() << "\"" << std::endl;
				}
			}
		}

		// Ferme le fichier de destination
		fichierAgrege.close();
	}
	else {
		std::cerr << "Impossible d'ouvrir le fichier source : \"" <<
			cheminFichier << "\"" << std::endl;
	}
}