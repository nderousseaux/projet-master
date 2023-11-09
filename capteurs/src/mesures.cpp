#include "mesures.hpp"

/* Public */

float Mesures::genValeursFloat(float borneInf, float borneSupp) {
	std::random_device rdm;
	std::mt19937 gen(rdm());
	std::uniform_real_distribution<float> distr(borneInf, borneSupp);

	return distr(gen);
}

int Mesures::genValeursInt(int borneInf, int borneSupp) {
	std::random_device rdm;
	std::mt19937 gen(rdm());
	std::uniform_int_distribution<int> distr(borneInf, borneSupp);

	return distr(gen);
}