#ifndef MESURES_H
#define MESURES_H

#include <random>

class Mesures {
	/* Variables */
	private:
		std::string date_;
		float temperature_;
		float humidite_;
		double luminosite_;


	/* Constructeur et destructeur */
	public:
		Mesures(void) = default;
		~Mesures(void) = default;


	/* Getters */
	public:
		inline std::string getDate(void) const {
			return date_;
		}
		inline float getTemperature(void) const {
			return temperature_;
		}
		inline float getHumidite(void) const {
			return humidite_;
		}
		inline double getLuminosite(void) const {
			return luminosite_;
		}


	/* Setters */
	public:
		inline void setDate(std::string date) {
			this->date_ = date;
		}
		inline void setTemperature(float temperature) {
			this->temperature_ = temperature;
		}
		inline void setHumidite(float humidite) {
			this->humidite_ = humidite;
		}
		inline void setLuminosite(double luminosite) {
			this->luminosite_ = luminosite;
		}


	/* Méthodes */
	public:
		/**
		 * @brief Génère une valeur aléatoires entre borneInf et borneSupp
		 *
		 * @param borneInf la borne inférieur
		 * @param borneSupp la borne supérieur
		 * @param type le type de valeur à générer (0 = double | 1 = float)
		 * @return float/double la valeur aléatoire générée
		 */
		static float genValeur(float borneInf, float borneSupp, bool type);
};

#endif