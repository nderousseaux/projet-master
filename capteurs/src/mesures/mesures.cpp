#include "mesures.hpp"

/* Public */

float Mesures::genValeur(float borneInf, float borneSupp, bool type) {
	std::random_device rdm;
	std::mt19937 gen(rdm());

	// Génère un float
	if (type) {
		std::uniform_real_distribution<float> distr(borneInf, borneSupp);
		return distr(gen);
	}
	// Génère un double, sans décimales
	else {
		std::uniform_real_distribution<double> distr(borneInf, borneSupp);
		return trunc(distr(gen));
	}
}