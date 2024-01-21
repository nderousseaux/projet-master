#!/bin/bash 

#Parametre de la communication coap
INTERFACE=lo
PORT=5683
IP=192.168.199.1

MYFOLDER=/home/$SUDO_USER/test/mesure_
COAP_FOLDER=/home/$SUDO_USER/Coap

echo $MYFOLDER

#Lancement du serveur coap en arriere plan
sudo python3 $COAP_FOLDER/coap_serveur.py -i $IP -p $PORT -f $MYFOLDER
