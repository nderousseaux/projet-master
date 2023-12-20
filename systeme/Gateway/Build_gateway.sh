#!/bin/bash

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

# Emplacement du fichier
file="/etc/rc.local"

# Texte à ajouter
line_to_add="/home/pi/start-batman-adv.sh &"

# Compteurs pour suivre le nombre de fois que "exit 0" est trouvé
exit_count=0

# Parcourir le fichier ligne par ligne
while IFS= read -r line; do
    # Vérifier si la ligne contient "exit 0"
    if [[ $line == *"exit 0"* ]]; then
        ((exit_count++))
        # Ajouter la nouvelle ligne au-dessus du deuxième "exit 0"
        if [ $exit_count -eq 2 ]; then
            sed -i "s/exit 0/$line_to_add\nexit 0/" "$file"
            echo "Ligne ajoutée avec succès."
        fi
    fi
done < "$file"

#Configuration Gateway
## Configuration du serveur DHCP (interface bat0)
if sudo grep -q "dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h" /etc/dnsmasq.conf; then
    echo "La ligne existe déjà."
else
    echo "La ligne n'existe pas. Ajout en cours..."
    sudo echo "interface=bat0" >> /etc/dnsmasq.conf
    sudo echo "dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h" >> /etc/dnsmasq.conf
    echo "Lignes ajoutées avec succès."
fi

#(interface wlan0)
sudo echo "interface=wlan0" >> sudo /etc/dnsmasq.conf
sudo echo "dhcp-range=10.0.1.2,10.0.1.255,255.255.255.0,12h" >> sudo /etc/dnsmasq.conf

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