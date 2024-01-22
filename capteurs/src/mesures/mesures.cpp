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

float Mesures::updateHumidite() {
	int fd = open(I2C_DEV.c_str(), O_RDWR);

	if (fd < 0)
		return std::nanf("");
	if (ioctl(fd, I2C_SLAVE, HUMIDITE_ADDR) < 0)
		return std::nanf("");

	//requete de lecture
	if (write(fd, HUMIDITE_REGISTRES, 2) != 2)
		return std::nanf("");

	char buffer[2];
	if (read(fd, buffer, 2) != 2)
		return std::nanf("");

	int humidite_absolue = (buffer[1] + (buffer[0] << 8));

	if (humidite_absolue < HUMIDITE_LOW_THRESHOLD)
		return 0.0f;
	else
		return 100 * ((float)humidite_absolue - HUMIDITE_LOW_THRESHOLD) /
			(HUMIDITE_HIGH_THRESHOLD - HUMIDITE_LOW_THRESHOLD);
}

float Mesures::updateTemperature() {
    return 0.0f;
}

double Mesures::updateLuminosite() {

	const char* script = "src/mesures/lux.py";
    double res = 0;

	std::string cmd = "python3 " + std::string(script);

	try {
		FILE* pipe = popen(cmd.c_str(), "r");
		
		char buffer[128];
		std::string res = "";
		while (fgets(buffer, sizeof(buffer), pipe) != nullptr) {
			res += buffer;
		}

		pclose(pipe);

		int value;
		std::istringstream(res) >> value;

		res = value;

	} catch (const std::exception& e) {}

	return res;

//////////////
	int fd = open(I2C_DEV.c_str(), O_RDWR);

	if (fd < 0)
		return std::nan("");
	if (ioctl(fd, I2C_SLAVE, LUMINOSITE_ADDR) < 0)
		return std::nan("");

	//Configuration du capteur
	if (write(fd, "\0\0\0", 3) != 3)
		return std::nan("");

	//requete de lecture
	if (write(fd, LUMINOSITE_REGISTRES, 2) != 2)
		return std::nan("");

	char buffer[2];
	if (read(fd, buffer, 2) != 2)
		return std::nan("");

	return buffer[1] + (buffer[0] << 8);
}
