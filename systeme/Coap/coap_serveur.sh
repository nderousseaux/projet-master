#!/bin/bash 

INTERFACE=lo
PORT=5683
IP=127.0.0.1

IN=/etc/coap/in.txt

# Contenu du fichier sftp_config.conf
HOST=185.155.93.103
USER=sftp_user
PASSWORD=pm2324
LOCAL_FILE=/etc/coap/in.txt
REMOTE_DIR=upload
sftp_command="put $LOCAL_FILE $REMOTE_DIR"

#Lancement du serveur coap en arriere plan
sudo python3 coapserveur.py -i $IP -p $PORT -f $IN &

last_modified=$(stat -c %Y "$file_to_watch")

#Communication SFTP (reset in.txt et l'envoie au serveur)
while true; do 
    if [ $(stat -c %Y "$LOCAL_FILE") -gt $last_modified ]; then
        last_modified=$(stat -c %Y "$file_to_watch")
        sshpass -p $PASSWORD sftp $USER@$HOST << EOF
        $sftp_command
        quit
EOF
    sleep 60
done
