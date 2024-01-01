#include "mesures.hpp"

/* Public */

int Mesures::updateLux() {
    int fd = open(I2C_DEVICE.c_str(), O_RDWR);

    if (fd < 0) {

    }

    if (ioctl(fd, I2C_SLAVE, LUX_ADDR) < 0) {

    }
    return 0;
}

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