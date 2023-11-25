#!/bin/bash

mongosh --port 30001 <<EOF
use data;
db.compte.insertOne(
    {
        idUser: 0,
	idAgri: 0,
	role: "admin",
        nom: "John",
        prenom: "Doe",
        mail: "email@bidon.eu",
        mdp: "helloworld"
    }
);

db.agriculteur.insertOne(
    {
        idAgri: 0,
        nomAgri: "agriDuRhin",
        champs: {
            coordonnees: [[48.526617, 7.73894]],
            ilots: [4]
        }
    }
);

db.humi.insertOne(
    {
        idAgri: 0,
        idChamps: 0,
        date: new Date(),
        valeurs: [45, 55, 41, 53]
    }
);


db.temp.insertOne(
    {
        idAgri: 0,
        idChamps: 0,
        date: new Date(),
        valeurs: [25, 26, 28, 23]
    }
);

db.lumi.insertOne(
    {
        idAgri: 0,
        idChamps: 0,
        date: new Date(),
        valeurs: [405, 550, 410, 337]
    }
);
EOF
