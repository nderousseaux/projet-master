#!/bin/bash 

#Parametre de la communication coap
INTERFACE=lo
PORT=5683
IP=127.0.0.1

MYFOLDER=~/stockage

# Contenu du fichier sftp_config.conf
HOST=185.155.93.103
USER=sftp_user
PASSWORD=pm2324
LOCAL_FILE=$MYFOLDER/mesure_
REMOTE_DIR=upload

#Lancement du serveur coap en arriere plan
sudo python3 coap_serveur.py -i $IP -p $PORT -f $LOCAL_FILE