## Démarrer le replica set:
```
./setup.sh      # créé les dossiers des dbs(data/*)
./start.sh      # lance conteneurs et initie replica set avec conf de rs-init.sh
```

## Infos utiles:
* se connecter à l'instance mongo primaire par défaut:  
    ```
    docker exec -it mongo1 mongosh --port 30001
    ```
* url de connection au replica set:
    ```
    mongodb://mongo1:30001,mongo2:30002,mongo3:30003/?replicaSet=rs0
    ```

## Structure de la BDD

```
{
    "utilisateur": {
        "ID": "id utilisateur",
        "Nom": "nom",
        "Prénom": "prénom",
        "email": "email@bidon.eu",
        "mot de passe": "mdp"
    },

    "agriculteur": {
        "ID_agriculteur": "IDa",
        "Nom_agriculteur": "NOMa",
        "champs": {
            "coordonnées" : ["(lat, long) champs 0", "(lat, long) champs 1", "..."],
            "ilots": ["nb ilots champs 0", "nb ilots champs 1", "..."]
        }

    },

    "humidité": {
        "ID_agriculteur": "IDa",
        "ID_champs": "IDc",
        "horodatage": "date+heure",
        "valeurs": ["humidité ilot 0", "humidité ilot 1", "..."]
    },

    "température": {
        "ID_agriculteur": "IDa",
        "ID_champs": "IDc",
        "horodatage": "date+heure",
        "valeurs": ["température ilot 0", "température ilot 1", "..."]
    },

    "luminosité": {
        "ID_agriculteur": "IDa",
        "ID_champs": "IDc",
        "horodatage": "date+heure",
        "valeurs": ["luminosité ilot 0", "luminosité ilot 1", "..."]
    }
}
```