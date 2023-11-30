#ifndef STOCKAGE_DONNEES_HPP
#define STOCKAGE_DONNEES_HPP

#include "mesures.hpp"
#include "infosChamp.hpp"

#include <chrono>
#include <ctime>
#include <filesystem>
#include <fstream>
#include <iostream>

class StockageDonnees {
	/* Variable */
	public:
		inline static std::string DOSSIER_STOCKAGE {
			"./stockage/"
		};
		inline static std::string NOM_FICHIER_AGREGE {
			"agrege.txt"
		};
		inline static std::string CHEMIN_FICHIER_AGREGE {
			DOSSIER_STOCKAGE + NOM_FICHIER_AGREGE
		};


	/* Constructeur et destructeur */
	public:
		StockageDonnees(void) = default;
		~StockageDonnees(void) = default;


	/* Méthodes */
	public:
		/**
		 * @brief Ecrit les données des différentes mesures dans un fichier
		 * 
		 * @param nomFichier où écrire les données
		 * @param infosChamp informations du champ
		 * @param mesures mesures à écrire
		 * @return int EXIT_SUCCESS ou EXIT_FAILURE
		 */
		static int ecrireDonneesFichier(
			const std::string& nomFichier,
			InfosChamp infosChamp,
			Mesures mesures
		);

		/**
		 * @brief Génère la date actuelle au format UTC
		 * 
		 * @return std::string date au format "AAAA-MM-JJ HH:MM:SS"
		 */
		static std::string dateUTCActuelle(void);

		/**
		 * @brief Agrège les fichiers de données en un seul
		 */
		static void agregeFichiersDonnees(void);
};

#endif