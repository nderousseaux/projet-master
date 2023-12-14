#!/bin/bash

# Donne les droits d'execution d'Installation.sh
chmod +x ../Installation.sh

yes | ../Installation.sh

mv start-batman-adv.sh ~/start-batman-adv.sh
chmod +x ~/start-batman-adv.sh
echo 'batman-adv' | sudo tee --append /etc/modules
echo 'denyinterfaces wlan0' | sudo tee --append /etc/dhcpcd.conf

fichier="/etc/rc.local"

# Vérifier si le fichier existe
if [ ! -f "$fichier" ]; then
    echo "Le fichier $fichier n'existe pas."
    exit 1
fi

# Ligne à ajouter
nouvelle_ligne="/home/pi/start-batman-adv.sh &"

# Ajouter la nouvelle ligne juste avant la ligne "exit 0"
sed -i "/exit 0/i $nouvelle_ligne" "$fichier"
