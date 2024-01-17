#!/bin/bash 

# Vérifier si un argument est fourni
if [ $# -eq 0 ]; then
    echo "Usage: $0 <argument>"
    exit 1
fi

# Récupérer l'argument
arg_USER=$1

# Afficher l'argument
echo "L'argument fourni est : $arg_USER"

INTERFACE=lo
PORT=5683
IP=127.0.0.1

folder_to_watch=/home/$arg_USER/stockage
Folder=/home/$arg_USER/systeme/Coap
COUNT=0

if [ "$(ls -A $folder_to_watch)" ]; then
    # Itérer sur chaque fichier dans le dossier
    for file in "$folder_to_watch"/*; do
        if [ -f "$file" ]; then
        echo "$file"
        # Le dossier n'est pas vide, exécutez la commande
        python3 $Folder/coapclient.py -o POST -p "coap://$IP:$PORT/basic" -f "$file"
        #sudo rm $file 
        fi 
    done 
    # Ajoutez une pause (sleep) si nécessaire avant la prochaine itération
fi
