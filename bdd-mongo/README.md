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
    "compte": {
        "idUser": "id utilisateur",
        "idAgri": "IDa",
        "nom": "nom",
        "prenom": "prénom",
        "mail": "email@bidon.eu",
        "mdp": "mdp"
    },

    "agriculteur": {
        "idAgri": "IDa",
        "nomAgri": "NOMa",
        "champs": {
            "coords" : ["(lat, long) champs 0", "(lat, long) champs 1", "..."],
            "ilots": ["nb ilots champs 0", "nb ilots champs 1", "..."]
        }

    },

    "humi": {
        "idAgri": "IDa",
        "idChamps": "IDc",
        "horo": "date+heure",
        "valMesures": ["humidité ilot 0", "humidité ilot 1", "..."]
    },

    "temp": {
        "idAgri": "IDa",
        "idChamps": "IDc",
        "horo": "date+heure",
        "valMesures": ["température ilot 0", "température ilot 1", "..."]
    },

    "lumi": {
        "idAgri": "IDa",
        "idChamps": "IDc",
        "horo": "date+heure",
        "valMesures": ["luminosité ilot 0", "luminosité ilot 1", "..."]
    }
}
```