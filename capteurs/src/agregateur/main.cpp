#include "agregateurBdd.h"

int main(int argc, char** argv) {
	// Vérifie le nombre d'arguments
	if (argc != 1) {
		std::cerr << "Usage: " << argv[0] << std::endl;
		return EXIT_FAILURE;
	}

	// Ouvre la base de données
	int retour = 0;
	BaseDeDonnees baseDeDonnees;

	retour = baseDeDonnees.ouvrirBaseDeDonnees(NOM_BDD_AGR);
	testErreur(retour, "Impossible d'ouvrir la base de données");

	// agrége les données
	retour = baseDeDonnees.agregerMesures();

	// Ferme la base de données
	retour = baseDeDonnees.fermerBaseDeDonnees();
	testErreur(retour, "Impossible de fermer la base de données");

	return EXIT_SUCCESS;
}