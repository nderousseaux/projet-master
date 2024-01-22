#ifndef MESURES_H
#define MESURES_H

#include <fcntl.h>
#include <linux/i2c-dev.h>
#include <random>
#include <sys/ioctl.h>
#include <unistd.h>
#include <cstdlib>
#include <sstream>
#include <string>
#include <iostream>

class Mesures {
	/* Variables */
	private:
		std::string date_;
		float temperature_;
		float humidite_;
		double luminosite_;

		static constexpr int HUMIDITE_HIGH_THRESHOLD = 520;
		static constexpr int HUMIDITE_LOW_THRESHOLD = 400;
		static constexpr int HUMIDITE_ADDR = 0x36;

		// Valeurs donnés ici : https://learn.adafruit.com/adafruit-stemma-soil-sensor-i2c-capacitive-moisture-sensor/faq
		static constexpr unsigned char HUMIDITE_REGISTRES[] = {
				0x0F, // base register
				0x10  // function register
		};
		static constexpr int LUMINOSITE_ADDR = 0x10;

		// Valeurs donnés ici : https://www.vishay.com/docs/84286/veml7700.pdf
		static constexpr unsigned char LUMINOSITE_REGISTRES[] = {
				0x04,
				0x0
		};

		static inline std::string I2C_DEV = "/dev/i2c-1";

	/* Constructeur et destructeur */
	public:
		Mesures(void) = default;
		~Mesures(void) = default;


	/* Getters */
	public:
		[[nodiscard]] inline std::string getDate(void) const {
			return date_;
		}
		[[nodiscard]] inline float getTemperature(void) const {
			return temperature_;
		}
		[[nodiscard]] inline float getHumidite(void) const {
			return humidite_;
		}
		[[nodiscard]] inline double getLuminosite(void) const {
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
         * @brief lit l'humidite du capteur
         *
         * @return la valeur lu par le capteur
         */
		static float updateHumidite(void);

        /**
         * @brief lit la luminosité du capteur
         *
         * @return la valeur lu par le capteur
         */
		static double updateLuminosite(void);

        /**
         * @brief lit la temperature du capteur
         *
         * @return la valeur lu par le capteur
         */
		static float updateTemperature(void);

		/**
		 * @brief Génère une valeur aléatoires entre borneInf et borneSupp
		 *
		 * @param borneInf la borne inférieur
		 * @param borneSupp la borne supérieur
		 * @param type le type de valeur à générer (0 = float | 1 = double)
		 * @return float/double la valeur aléatoire générée
		 */
		static float genValeur(float borneInf, float borneSupp, bool type);
};

#endif
