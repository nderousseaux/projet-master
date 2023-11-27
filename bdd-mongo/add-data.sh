#!/bin/bash

mongosh --port 30001 <<EOF
use data;
db.compte.insertMany( [
    {
        idUser: 0,
	    idAgri: 0,
	    role: "admin",
        nom: "John",
        prenom: "Doe",
        mail: "email@bidon.eu",
        mdp: "helloworld"
    },
    {
        idUser: 1,
	    idAgri: 0,
	    role: "standard",
        nom: "Jean",
        prenom: "Dupont",
        mail: "email2@bidon.eu",
        mdp: "helloworld"
    },
    {
        idUser: 2,
	    idAgri: 1,
	    role: "admin",
        nom: "John",
        prenom: "Jones",
        mail: "email3@bidon.eu",
        mdp: "helloworld"
    }
] );

db.agriculteur.insertMany( [
    {
        idAgri: 0,
        nomAgri: "agriDuRhin",
        champs: {
            coordonnees: [[48.526617, 7.73894], [51.034, 2.376], [48.525, 7.737]],
            ilots: [4, 3, 2]
        }
    },
    {
        idAgri: 1,
        nomAgri: "ChampsDeLest",
        champs: {
            coordonnees: [[48.1, 7.33]],
            ilots: [5]
        }
    }
] );

db.humi.insertMany( [
    {
        idAgri: 0,
        idChamps: 0,
        dates: [new Date(), new Date(), new Date(), new Date()],
        valeurs: [45.1234, 55.1234, 41.1324, 53.4845]
    },
    {
        idAgri: 0,
        idChamps: 1,
        dates: [new Date(), new Date(), new Date()],
        valeurs: [47.4687, 43.1374, 55.6819]
    },
    {
        idAgri: 1,
        idChamps: 0,
        dates: [new Date(), new Date(), new Date(), new Date(), new Date()],
        valeurs: [23, 29, 46, 44, 67]
    },
    {
        idAgri: 0,
        idChamps: 2,
        dates: [new Date(), new Date()],
        valeurs: [23.4674, 29.4545]
    }
] );


db.temp.insertMany( [
    {
        idAgri: 0,
        idChamps: 0,
        dates: [new Date(), new Date(), new Date(), new Date()],
        valeurs: [25.1234, 26.3415, 28.2684, 23.1679]
    },
    {
        idAgri: 0,
        idChamps: 1,
        dates: [new Date(), new Date(), new Date()],
        valeurs: [27.4687, 23.1374, 25.6819]
    },
    {
        idAgri: 1,
        idChamps: 0,
        dates: [new Date(), new Date(), new Date(), new Date(), new Date()],
        valeurs: [23.4568, 29.1597, 46.6479, 44.1159, 67.2345]
    },
    {
        idAgri: 0,
        idChamps: 2,
        dates: [new Date(), new Date()],
        valeurs: [23.9674, 24.3545]
    }
] );

db.lumi.insertMany( [
    {
        idAgri: 0,
        idChamps: 0,
        dates: [new Date(), new Date(), new Date(), new Date()],
        valeurs: [405, 550, 410, 337]
    },
    {
        idAgri: 0,
        idChamps: 1,
        dates: [new Date(), new Date(), new Date()],
        valeurs: [945, 1000, 887]
    },
    {
        idAgri: 1,
        idChamps: 0,
        dates: [new Date(), new Date(), new Date(), new Date(), new Date()],
        valeurs: [568, 597, 479, 159, 345]
    },
    {
        idAgri: 0,
        idChamps: 2,
        dates: [new Date(), new Date()],
        valeurs: [501, 808]
    }
] );

EOF
