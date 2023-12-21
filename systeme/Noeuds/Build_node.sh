#!/bin/bash

# Donne les droits d'execution d'Installation.sh
chmod +x ../Installation.sh

yes | ../Installation.sh

# Donne les droits d'execution d'Installation.sh
chmod +x ../Installation.sh

yes | ../Installation.sh

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
line_to_add="/home/pi/start-batman-adv.sh &"

sed -i "\|^\"exit 0\"|! s|exit 0|$line_to_add\nexit 0|" "$fichier"

echo "Le systeme va redemarrer dans 5 secondes"
sleep 5

sudo reboot
