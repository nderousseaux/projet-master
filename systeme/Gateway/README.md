# README GATEWAY

## Installation avec Script
Vous pouvez lancer le script `sudo ./Build_gateway.sh` pour l'installation. Celui installera également les paquets nécessaire à l'aide du script `../Installation.sh`.

## Installation manuel
```bash
# Arrêt du NetworkManager
sudo systemctl stop NetworkManager
sudo systemctl disable NetworkManager

# Création du fichier de configuration qui sera exécuté à chaque démarrage
# Text comportant (voir system/Gateway/start-batman-adv.sh)
nano ~/start-batman-adv.sh
# Ajout des droits d'excution 
chmod +x ~/start-batman-adv.sh

# Ajout du protocole batman
echo 'batman-adv' | sudo tee --append /etc/modules
# Ajout de la configuration dhcp
echo 'denyinterfaces wlan0' | sudo tee --append /etc/dhcpd.conf
# Ajout du fichier de configuration start-batman-adv.sh
# La ligne est à ajouter avant le exit 0
# /home/<VotreNomUtilisateur>/start-batman-adv.sh &
sudo nano /etc/rc.local

# Configuration Gateway
# Configuration du serveur DHCP sur l'interface bat0
sudo echo "interface=bat0" >> /etc/dnsmasq.conf
sudo echo "dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h" >> /etc/dnsmasq.conf
# Interface wlan0
sudo echo "interface=wlan0" >> /etc/dnsmasq.conf
sudo echo "dhcp-range=10.0.1.2,10.0.1.255,255.255.255.0,12h" >> /etc/dnsmasq.conf
```


### Remarque
[Verifier l'installation du gateway](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/ROUTE.md#verifying-the-gateway)

- sudo batctl if : Pour afficher les interfaces qui participent au mesh network
- sudo batctl n : Pour afficher les voisins qui participent au mesh que le gateway peut voir.

## Bibliographie 
[Tuto installation Gateway](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/ROUTE.md#creating-the-gateway)




