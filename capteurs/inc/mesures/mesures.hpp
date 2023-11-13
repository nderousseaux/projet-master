#ifndef MESURES_H
#define MESURES_H

#include <random>

class Mesures {
	/* Variables */
	private:
		float temperature_;
		float humidite_;
		int luminosite_;


	/* Constructeur et destructeur */
	public:
		Mesures(void) = default;
		~Mesures(void) = default;


	/* Getters */
	public:
		inline float getTemperature(void) const {
			return temperature_;
		}

		inline float getHumidite(void) const {
			return humidite_;
		}

		inline int getLuminosite(void) const {
			return luminosite_;
		}


	/* Setters */
	public:
		inline void setTemperature(float temperature) {
			this->temperature_ = temperature;
		}

		inline void setHumidite(float humidite) {
			this->humidite_ = humidite;
		}

		inline void setLuminosite(int luminosite) {
			this->luminosite_ = luminosite;
		}


	/* Méthodes */
	public:
		/**
		 * @brief Génère des valeurs aléatoires entre borneInf et borneSupp
		 *
		 * @param borneInf la borne inférieur
		 * @param borneSupp la borne supérieur
		 * @return float/int la valeur aléatoire générée
		 */
		static float genValeursFloat(float borneInf, float borneSupp);
		static int genValeursInt(int borneInf, int borneSupp);
};

#endif