#!/bin/bash
# batman-adv interface to use
sudo batctl if add wlan0
sudo ifconfig bat0 mtu 1468

# Tell batman-adv this is a gateway client
sudo batctl gw_mode client 

# Activates batman-adv interfaces
sudo ifconfig wlan0 up
sudo ifconfig bat0 up

#sudo iwconfig wlan0 mode ad-hoc
#sudo iwconfig wlan0 channel 1
#sudo iwconfig wlan0 call-code-mesh

# Forcer le mode ad-hoc aussi nommé "ibss"
sudo iw dev wlan0 set type ibss
# Forcer le ESSID
sudo iw dev wlan0 ibss join call-code-mesh 2462

sudo dhclient wlan0 
