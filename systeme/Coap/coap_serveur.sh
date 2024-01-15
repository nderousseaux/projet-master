#!/bin/bash 

#Parametre de la communication coap
INTERFACE=lo
PORT=5683
IP=10.0.1.1

MYFOLDER=~/stockage

#Lancement du serveur coap en arriere plan
sudo python3 coap_serveur.py -i $IP -p $PORT -f $LOCAL_FILE