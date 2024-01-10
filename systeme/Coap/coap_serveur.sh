#!/bin/bash 

#Parametre de la communication coap
INTERFACE=lo
PORT=5683
IP=127.0.0.1

MYFOLDER=/capteurs/stockage

# Contenu du fichier sftp_config.conf
HOST=185.155.93.103
USER=sftp_user
PASSWORD=pm2324
LOCAL_FILE=$MYFOLDER/mesure_
REMOTE_DIR=upload

#Lancement du serveur coap en arriere plan
sudo python3 coapserveur.py -i $IP -p $PORT -f $LOCAL_FILE

#Communication SFTP (reset in.txt et l'envoie au serveur)
#while true; do
#    sshpass -p $PASSWORD sftp $USER@$HOST << EOF
#    put $LOCAL_FILE $REMOTE_DIR
#    current_datetime=$(date +"%Y%m%d_%H%M%S")
#    new_filename="${current_datetime}_in.txt"
#    mv $REMOTE_DIR/in.txt $new_filename
#    quit
#EOF
#    sleep 60
#    fi
#done
