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