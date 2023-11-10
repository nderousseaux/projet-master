#ifndef ENREGISTREMENT_H
#define ENREGISTREMENT_H

#include "infosChamp.hpp"
#include "mesures.hpp"

#include <fstream>
#include <iomanip>
#include <sqlite3.h>
#include <sstream>
#include <sys/stat.h>

class BaseDeDonnees {
	/* Variables */
	private:
		inline static std::string DOSSIER_BDD{
			"./bdd/"
		};
		inline static std::string FICHIER_BDD{
			"mesures.sqlite3"
		};
		sqlite3* db_{nullptr};


	/* Constructeur et destructeur */
	public:
		BaseDeDonnees(void) = default;
		~BaseDeDonnees(void) = default;


	/* Getter */
	private:
		inline sqlite3* getDbHandler(void) const {
			return db_;
		}


	/* Setter */
	private:
		inline void setDbHandler(sqlite3* db) {
			db_ = db;
		}


	/* Méthodes */
	public:
		/**
		 * @brief Ouvre la base de données, ou la créé si elle n'existe pas et
		 * 		  enregistre le handler de la base de données dans la classe
		 *
		 * @return int EXIT_SUCCESS ou EXIT_FAILURE
		 */
		int ouvrirBaseDeDonnees(void);

		/**
		 * @brief Vide le contenu de la base de données
		 *
		 * @return int EXIT_SUCCESS ou EXIT_FAILURE
		 */
		int nettoyerBaseDeDonnees(void);

		/**
		 * @brief Enregistre les mesures dans la base de données
		 *
		 * @param infosChamp les informations sur le champ (id champ, id ilot,
		 * 					 id capteur)
		 * @param mesures les mesures à enregistrer
		 *
		 * @return int EXIT_SUCCESS ou EXIT_FAILURE
		 */
		int insererMesures(InfosChamp infosChamp, Mesures mesures);
};

#endif