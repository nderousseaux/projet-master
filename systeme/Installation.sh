#!/bin/bash
#Intégralité des paquets à installer

sudo apt update
sudo apt upgrade

# Pour faire fonctionner le script test :
sudo apt install cmake
sudo apt install libssh2-1-dev
sudo apt-get install libsqlite3-dev

# Pour le réseau mesh :
sudo apt-get install -y batctl

# Seulement pour le Gateway :
sudo apt-get install -y dnsmasq

# Pour connecter et tester le traffic
sudo apt install npm
sudo npm install -g --unsafe-perm node-red
sudo npm -g install node-pre-gyp
sudo npm -g install node-gyp