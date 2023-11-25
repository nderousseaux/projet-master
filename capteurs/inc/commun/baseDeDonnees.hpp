#ifndef ENREGISTREMENT_H
#define ENREGISTREMENT_H

#include "infosChamp.hpp"
#include "mesures.hpp"

#include <arpa/inet.h>
#include <filesystem>
#include <fstream>
#include <iomanip>
#include <iostream>
#include <libssh2.h>
#include <libssh2_sftp.h>
#include <netinet/in.h>
#include <sqlite3.h>
#include <sstream>
#include <sys/stat.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <unistd.h>

class BaseDeDonnees {
	/* Variables */
	private:
		sqlite3* db_{nullptr};
		inline static std::string DOSSIER_BDD{
			"./bdd/"
		};


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
		 * @param nomBaseDeDonnees le nom du fichier de la base de données
		 * @return int EXIT_SUCCESS ou EXIT_FAILURE
		 */
		int ouvrirBaseDeDonnees(std::string nomBaseDeDonnees);

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

		/**
		 * @brief Agrége les mesures stockées dans les bases de données en une
		 * 		  seule
		 * 
		 * @return int EXIT_SUCCESS ou EXIT_FAILURE
		 */
		int agregerMesures(void);

		/**
		 * @brief Ferme la base de données
		 *
		 * @return int EXIT_SUCCESS ou EXIT_FAILURE
		 */
		int fermerBaseDeDonnees(void);

		int envoiBDD();

        int conversionDBversChar(char* buffer);
};

#endif