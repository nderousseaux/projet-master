#include "mesures.hpp"

/* Public */

int Mesures::updateLux() {
    int fd = open(I2C_DEVICE.c_str(), O_RDWR);

    if (fd < 0) {
        printf("Erreur : Impossible d'ouvrir %s\n", I2C_DEVICE.c_str());
        return 1;
    }

    if (ioctl(fd, I2C_SLAVE, LUX_ADDR) < 0) {
        printf("Erreur : Impossible de regler l'adresse du peripherique i2c\n");
        close(fd);
        return 2;
    }

    int buff[2];
    buff[0] = LUX_PTR_VALUE;
    if (write(fd, buff, 1) != 1) {
        printf("Erreur : Impossible d'ecrire sur le registre\n");
        close(fd);
        return 3;
    }

    if (read(fd, buff, 2) != 2) {
        printf("Erreur : Impossible de lire la valeur de la luminosite\n");
        close(fd);
        return 4;
    }

    luminosite_ = buff[0] + 10 * buff[1];

    close(fd);
    return 0;
}

int Mesures::updateHumidity() {
    int fd = open(I2C_DEVICE.c_str(), O_RDWR);

    if (fd < 0) {
        printf("Erreur : Impossible d'ouvrir %s\n", I2C_DEVICE.c_str());
        return 1;
    }

    if (ioctl(fd, I2C_SLAVE, HUM_ADDR) < 0) {
        printf("Erreur : Impossible de regler l'adresse du peripherique i2c\n");
        close(fd);
        return 2;
    }

    int buff[2];
    buff[0] = HUM_PTR_VALUE;
    if (write(fd, buff, 1) != 1) {
        printf("Erreur : Impossible d'ecrire sur le registre\n");
        close(fd);
        return 3;
    }

    int raw_value = buff[0] + buff[1] * 10;
    normalizeHumidity(raw_value);

    if (read(fd, buff, 2) != 2) {
        printf("Erreur : Impossible de lire la valeur de l'humidite\n");
        close(fd);
        return 4;
    }

    close(fd);
    return 0;
}

void Mesures::normalizeHumidity(const int &raw_value) {
    if (raw_value < HUM_THRESHOLD_LOW) {
        humidite_ = 0.0f;
        return;
    }

    float diff = HUM_THRESHOLD_HIGH - HUM_THRESHOLD_LOW;
    float delta = (float)raw_value - HUM_THRESHOLD_LOW;
    humidite_ =  delta / diff;
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