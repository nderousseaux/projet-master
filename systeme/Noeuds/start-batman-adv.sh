#!/bin/bash
# interface à utiliser pour batman-adv
sudo batctl if add wlan0
sudo ifconfig bat0 mtu 1468

# Le noeud est definit comme un client de la passerelle
sudo batctl gw_mode client

# Mise en place de wlan0
sudo ifconfig wlan0 down

sudo iwconfig wlan0 mode ad-hoc
sudo iwconfig wlan0 essid call-code-mesh
sudo iwconfig wlan0 channel 1 frequency 2462
# ou sudo iw dev wlan0 set freq 2462

sudo dhclient wlan0 

# Activates batman-adv interfaces
sudo ifconfig wlan0 up
sudo ifconfig bat0 up
sudo ifconfig bat0 192.168.199.1/24

bash ../Coap/coap_client.sh