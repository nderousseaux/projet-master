#!/bin/bash 

#Parametre de la communication coap
INTERFACE=lo
PORT=5683
IP=127.0.0.1

MYFOLDER=/home/$SUDO_USER/test/mesure_

echo $MYFOLDER

#Lancement du serveur coap en arriere plan
sudo python3 coap_serveur.py -i $IP -p $PORT -f $MYFOLDER
