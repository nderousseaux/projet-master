#!/bin/bash 

INTERFACE=lo
PORT=5683
IP=127.0.0.1

folder_to_watch="/etc/coap"
OUT=$folder_to_watch/out.txt
COUNT=0


inotifywait -m -e create --format '%w%f' "${folder_to_watch}" | while read file
do
    ((COUNT++))
    if ((COUNT % 2 == 1)); then
        # La commande à exécuter lorsqu'un fichier est créé
        sudo python3 coapclient.py -o POST -p coap://$IP:$PORT/basic -f $OUT

        current_time=$(date "+%Y%m%d_%H%M%S")
        file_name="fichier_${current_time}.txt"

        mv 
    fi
done