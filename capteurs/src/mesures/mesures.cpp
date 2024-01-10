#include "mesures.hpp"

/* Public */

float Mesures::genValeur(float borneInf, float borneSupp, bool type) {
	std::random_device rdm;
	std::mt19937 gen(rdm());

	// Génère un float
	std::uniform_real_distribution<double> distr(borneInf, borneSupp);
	const double valeur = distr(gen);

	if (type)
		return trunc(valeur);

	return valeur;
}