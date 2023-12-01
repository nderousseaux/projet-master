#!/bin/bash
# Pas fonctionnel

#yes | ../Installation.sh

mv start-batman-adv.sh ~/start-batman-adv.sh
chmod +x ~/start-batman-adv.sh
mv wlan0 /etc/network/interfaces.d/wlan0
echo 'batman-adv' | sudo tee --append /etc/modules
echo 'denyinterfaces wlan0' | sudo tee --append /etc/dhcpcd.conf

#Manque la derniere etape