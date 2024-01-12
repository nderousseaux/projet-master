#!/bin/bash 

INTERFACE=lo
PORT=5683
IP=127.0.0.1

folder_to_watch="~/stockage"
COUNT=0

#!/bin/bash

while true; do
    if [ "$(ls -A $folder_to_watch)" ]; then
        # Itérer sur chaque fichier dans le dossier
        for file in "$folder_to_watch"/*; do
            if [ -f "$file" ]; then
            echo "$file"
            # Le dossier n'est pas vide, exécutez la commande
            sudo python3 coapclient.py -o POST -p "coap://$IP:$PORT/basic" -f "$file"
            sudo rm $file 
            fi 
        done 
        # Ajoutez une pause (sleep) si nécessaire avant la prochaine itération
        sleep 30  # Pause de 5 secondes avant de répéter la boucle
    fi
done
