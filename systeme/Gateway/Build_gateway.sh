#!/bin/bash
# Pas fonctionnel

#yes | ../Installation.sh

#Configuration noeud
mv start-batman-adv.sh ~/start-batman-adv.sh
chmod +x ~/start-batman-adv.sh
sudo mv ../Noeud/wlan0 /etc/network/interfaces.d/wlan0
echo 'batman-adv' | sudo tee --append /etc/modules
echo 'denyinterfaces wlan0' | sudo tee --append /etc/dhcpcd.conf

# Manque la derniere etape

#Configuration Gateway
sudo echo "interface=bat0" >> /etc/dnsmasq.conf
sudo echo "dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h" >> /etc/dnsmasq.conf