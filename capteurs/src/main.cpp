#include "main.h"

void testErreur(int retour, std::string message) {
	if (retour != EXIT_SUCCESS)
		std::cerr << message << std::endl;
}

int main(int argc, char** argv) {
	// Vérifie le nombre d'arguments
	if (argc != 4) {
		std::cerr << "Usage: " <<
			argv[0] <<
			" <id champ> <id ilot> <id capteur>" <<
			std::endl;
		return EXIT_FAILURE;
	}
	else {
		if (DEBUG) {
			std::cout << "Id champ : " << argv[1] << std::endl;
			std::cout << "Id ilot : " << argv[2] << std::endl;
			std::cout << "Id capteur : " << argv[3] << std::endl << std::endl;
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
	InfosChamp infosChamp;
	infosChamp.setInfosChamp(atoi(argv[1]), atoi(argv[2]), atoi(argv[3]));

	// Génère des valeurs factices
	Mesures mesures;
	mesures.setTemperature(Mesures::genValeursFloat(-20.0, 40.0));
	mesures.setHumidite(Mesures::genValeursFloat(0.0, 100.0));
	mesures.setLuminosite(Mesures::genValeursInt(0, 120000));

	// Affiche les valeurs générées
	if (DEBUG) {
		std::cout << "Temperature : " << mesures.getTemperature() << std::endl;
		std::cout << "Humidite : " << mesures.getHumidite() << std::endl;
		std::cout << "Luminosite : " << mesures.getLuminosite() << std::endl
			<< std::endl;
	}

	// Ouvrir la base de données
	int retour = 0;

	BaseDeDonnees baseDeDonnees;
	retour = baseDeDonnees.ouvrirBaseDeDonnees();
	testErreur(retour, "Impossible d'ouvrir la base de données");

	// Insère les données
	retour = baseDeDonnees.insererMesures(infosChamp, mesures);
	testErreur(retour, "Impossible d'insérer les mesures dans la base de"
		"données");

	return EXIT_SUCCESS;
}
