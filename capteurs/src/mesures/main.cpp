#include "recupMesures.h"

int main(int argc, char** argv) {
	// Vérifie le nombre d'arguments
	if (argc != 4) {
		std::cerr << "Usage: " <<
			argv[0] <<
			" <id agri> <id champ> <id ilot>" <<
			std::endl;

		return EXIT_FAILURE;
	}
	else {
		if (DEBUG) {
			std::cout << "Id agri : " << argv[1] << std::endl;
			std::cout << "Id champ : " << argv[2] << std::endl;
			std::cout << "Id ilot : " << argv[3] << std::endl << std::endl;
		}

		// Vérifie que les arguments sont des entiers positifs
		if (
			(atof(argv[1]) != atoi(argv[1]) || atoi(argv[1]) < 0) ||
			(atof(argv[2]) != atoi(argv[2]) || atoi(argv[2]) < 0) ||
			(atof(argv[3]) != atoi(argv[3]) || atoi(argv[3]) < 0)
		) {
			std::cerr << "Les arguments doivent être des entiers positifs"
				<< std::endl;
			return EXIT_FAILURE;
		}
	}
	// Stocke les informations du champ
	InfosChamp infosChamp;
	infosChamp.setInfosChamp(atoi(argv[1]), atoi(argv[2]), atoi(argv[3]));

	// Génère ou récupère des mesures
	Mesures mesures;
	float temp = 0, humi = 0;
	int lumi = 0;

		// Génère des valeurs factices
	if (SIMULATEUR) {
		temp = Mesures::genValeursFloat(-20.0, 40.0);
		humi = Mesures::genValeursFloat(0.0, 100.0);
		lumi = Mesures::genValeursInt(0, 120000);
	}
		// Récupère les valeurs des capteurs
	else {
		// >> Récupère ici les valeurs des capteurs
	}

	// Stocke les valeurs générées
	mesures.setDate(StockageDonnees::dateUTCActuelle());
	mesures.setTemperature(temp);
	mesures.setHumidite(humi);
	mesures.setLuminosite(lumi);

	// Affiche les valeurs générées
	if (DEBUG) {
		std::cout << "Temperature : " << mesures.getTemperature() << std::endl;
		std::cout << "Humidite : " << mesures.getHumidite() << std::endl;
		std::cout << "Luminosite : " << mesures.getLuminosite() << std::endl
			<< std::endl;
	}

	// Ecrit les données dans un fichier
	StockageDonnees::ecrireDonneesFichier("mesures.txt", infosChamp, mesures);

	// >> Envoie les données vers le serveur SFTP

	return EXIT_SUCCESS;
}