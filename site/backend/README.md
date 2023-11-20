# Backend

## Appels des fonctions PHP
Les fonctions JavaScript appelées dans `index.html` sont résumées à la fin du
document `index.html`.  
Les fonctions JavaScript, réalisant des requêtes au backend, se trouvent
dans le fichier `site/scripts/afficherDonnes.js`.  
La fonction, qui se charge de l'envoi des requêtes au backend, se trouve dans
`recupDonnees.js`. Elle permet de généraliser les requêtes en une unique
fonction, ayant pour paramètres : le nom du script PHP qui sera exécuté et
les champs envoyés en POST.

```
afficherNomUtilisateur          -->         recupNumUtilisateur.php
afficherChamps                  -->         recupNumChamps.php
afficherIlots                   -->         recupNumIlots.php
afficherInfosChamp              -->         recupInfosChamp.php
afficherMoyennes                -->         recupMoyennes.php
afficherTableauToutesMesures    -->         recupMesuresChamp.php
recupAbsOrdGraph (graphique.js) -->         recupMesuresIlot.php
```