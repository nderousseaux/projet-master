#ifndef ENVOI_DONNEES_HPP
#define ENVOI_DONNEES_HPP

#include "infosChamp.hpp"
#include "mesures.hpp"
#include "stockageDonnees.hpp"

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

class EnvoiDonnees {
	/* Variable */
	private:
		sqlite3* db_{nullptr};
		inline static std::string CHEMIN_FICHIER_IDENTIFIANTS {
			"./identifiantsSFTP.txt"
		};


	/* Constructeur et destructeur */
	public:
		EnvoiDonnees(void) = default;
		~EnvoiDonnees(void) = default;


	/* Méthodes */
	public:
		/**
		 * @brief Envoie les données de la base de données vers le serveur SFTP
		 * 
		 * @return int -1 si erreur, 0 sinon
		 */
		int envoiBDD(void);

		/**
		 * @brief Convertit les données de la base de données en chaîne de
		 * 		  caractères
		 * 
		 * @param buffer des données
		 * @return int -1 si erreur, 0 sinon
		 */
		int conversionBDDversChar(char* buffer);

        int initialisation_socket(const int& port, const char* ip);

        void affiche_message_erreur_libssh2(LIBSSH2_SESSION* session, const std::string& msg_erreur);

		/**
		 * @brief Récupère l'identifiant, le mot de passe et l'adresse IP du
		 * 		  serveur SFTP
		 * 
		 * @param cheminFichier du fichier contenant les informations
		 * @return std::vector<std::string> tableau contenant les informations
		 */
		static std::vector<std::string> recupereIdentifiants(
			const std::string cheminFichier
		);
};

#endif