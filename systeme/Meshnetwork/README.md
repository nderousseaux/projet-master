# Part 3 - Connect and test the sensors
[Tuto](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part3/SENSORS.md)

## Connect the sensor 

[Lien du tuto](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part3/SENSORS.md#step-1---connect-the-dht-sensor-to-your-raspberry-pi)

Ici on utilise l'execubale "mesures" avec des données randoms pour les tests

## Conf

Install Node.js on your Raspberry Pi
```bash
sudo apt-get install -y nodered
sudo npm -g install npm
sudo npm -g install node-pre-gyp
sudo npm -g install node-gyp
```

_Conf des sensors quel'on fait pas ici : [Lien](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part3/SENSORS.md#node-red-node-pi-neopixel-install-instructions)_ 

Reboot your Raspberry Pi mesh node
```bash
sudo reboot -n
```

Run on each RPI : 
```bash
node-red
```

Open a browser on your laptop to the IP Address or hostname.local of the desired Raspberry Pi. Node-RED is running on Port 1880 - http://pi_address:1880 or http://hostname.local:1880


