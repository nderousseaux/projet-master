#!/bin/bash

# Donne les droits d'execution d'Installation.sh
chmod +x ../Installation.sh

#yes | ../Installation.sh

#Configuration noeud
cp start-batman-adv.sh ~/start-batman-adv.sh
chmod +x ~/start-batman-adv.sh
echo 'batman-adv' | sudo tee --append /etc/modules
echo 'denyinterfaces wlan0' | sudo tee --append /etc/dhcpcd.conf

# Manque la derniere etape

fichier="/etc/rc.local"

# Vérifier si le fichier existe
if [ ! -f "$fichier" ]; then
    echo "Le fichier $fichier n'existe pas."
    exit 1
fi

# Ligne à ajouter
nouvelle_ligne="/home/pi/start-batman-adv.sh &"

# Ajouter la nouvelle ligne juste avant la ligne "exit 0"
sudo sed -i "/exit 0/i $nouvelle_ligne" "$fichier"

#Configuration Gateway
sudo echo "interface=bat0" >> sudo /etc/dnsmasq.conf
sudo echo "dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h" >> sudo /etc/dnsmasq.conf

sudo echo "interface=wlan0" >> sudo /etc/dnsmasq.conf
sudo echo "dhcp-range=10.0.1.2,10.0.1.255,255.255.255.0,12h" >> sudo /etc/dnsmasq.conf

echo "Le systeme va redemarrer dans 5 secondes"
sleep 5

sudo reboot


