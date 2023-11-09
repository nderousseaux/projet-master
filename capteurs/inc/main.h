#ifndef MAIN_H
#define MAIN_H

#include <iostream>

#include "baseDeDonnees.hpp"
#include "infosChamp.hpp"
#include "mesures.hpp"

/* Constante */

#define DEBUG 1

/* Fonction */

/**
 * @brief Affiche un message d'erreur si le retour est différent de EXIT_SUCCESS
 *
 * @param retour code de retour
 * @param message d'erreur à afficher
 */
void testErreur(int retour, std::string message);

#endif