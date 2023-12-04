#include "agregateurBdd.h"

int main(int argc, char** argv) {
	// Vérifie le nombre d'arguments
	if (argc != 1) {
		std::cerr << "Usage: " << argv[0] << std::endl;
		return EXIT_FAILURE;
	}

	// Agrège les fichiers de données
	StockageDonnees::agregeFichiersDonnees();

	return EXIT_SUCCESS;
}