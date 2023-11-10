#ifndef MAIN_H
#define MAIN_H

#include "baseDeDonnees.hpp"
#include "infosChamp.hpp"
#include "mesures.hpp"

#include <iostream>

/* Constante */

#define DEBUG 1			// Affiche les valeurs générées
#define SIMULATEUR 1	// Génère des valeurs factices (faux appareil)

/* Fonction */

/**
 * @brief Affiche un message d'erreur si le retour est différent de EXIT_SUCCESS
 *
 * @param retour code de retour
 * @param message d'erreur à afficher
 */
void testErreur(int retour, std::string message);

#endif