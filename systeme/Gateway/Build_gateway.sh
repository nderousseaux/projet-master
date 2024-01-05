#!/bin/bash

# Donne les droits d'execution d'Installation.sh
chmod +x ../Installation.sh
yes | ../Installation.sh

sudo systemctl stop NetworkManager

#Configuration noeud
cp start-batman-adv.sh ~/start-batman-adv.sh
chmod +x ~/start-batman-adv.sh
echo 'batman-adv' | sudo tee --append /etc/modules
echo 'denyinterfaces wlan0' | sudo tee --append /etc/dhcpcd.conf

fichier="/etc/rc.local"

# Vérifier si le fichier existe
if [ ! -f "$fichier" ]; then
    echo "Le fichier $fichier n'existe pas."
    exit 1
fi

# Texte à ajouter
line_to_add="/home/pi/start-batman-adv.sh \&"

sudo sed -i "\|^\"exit 0\"|! s|exit 0|$line_to_add \nexit 0|" "$fichier"

#Configuration Gateway
## Configuration du serveur DHCP (interface bat0)
sudo chmod +0700 /etc/dnsmasq.conf

if sudo grep -q "dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h" /etc/dnsmasq.conf; then
    echo "La ligne existe déjà."
else
    echo "La ligne n'existe pas. Ajout en cours..."
    sudo echo "interface=bat0" >> /etc/dnsmasq.conf
    sudo echo "dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h" >> /etc/dnsmasq.conf
    echo "Lignes ajoutées avec succès."
fi

#(interface wlan0)
sudo echo "interface=wlan0" >> /etc/dnsmasq.conf
sudo echo "dhcp-range=10.0.1.2,10.0.1.255,255.255.255.0,12h" >> /etc/dnsmasq.conf

if grep -q "dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h" /etc/dnsmasq.conf; then
    echo "La ligne existe déjà."
else
    echo "La ligne n'existe pas. Ajout en cours..."
    sudo echo "interface=wlan0" >> /etc/dnsmasq.conf
    sudo echo "dhcp-range=10.0.1.2,10.0.1.255,255.255.255.0,12h" >> /etc/dnsmasq.conf
    echo "Lignes ajoutées avec succès."
fi


echo "Le systeme va redemarrer dans 5 secondes"
sleep 5
sudo reboot
