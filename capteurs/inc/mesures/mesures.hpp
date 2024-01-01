#ifndef MESURES_H
#define MESURES_H

#include <fcntl.h>
#include <sys/ioctl.h>
#include <sys/stat.h>
#include <linux/ioctl.h>
#include <linux/types.h>
#include <linux/i2c-dev.h>

#include <random>

class Mesures {
	/* Variables */
	private:
		std::string date_;
		float temperature_;
		float humidite_;
		int luminosite_;

        inline static std::string I2C_DEVICE = {
                "/dev/i2c-1"
        };
        static constexpr int LUX_ADDR = 0x10;
        static constexpr int HUM_ADDR = 0x36;


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
		[[nodiscard]] inline int getLuminosite(void) const {
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
		inline void setLuminosite(int luminosite) {
			this->luminosite_ = luminosite;
		}


	/* Méthodes */
    private:
        static int updateLux();

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