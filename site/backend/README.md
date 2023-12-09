# Backend

## Appels des fonctions PHP
Les fonctions JavaScript appelées dans `index.html` se trouvent à la fin du
fichier `index.html`.  
Les fonctions JavaScript, réalisant des requêtes au backend, se trouvent
dans le fichier `site/scripts/afficherDonnees.js`.  
La fonction, qui se charge de l'envoi des requêtes au backend, se trouve dans
`site/scripts/recupDonnees.js`. Elle permet de généraliser les requêtes en une
unique fonction, ayant pour paramètres : le nom du script PHP qui sera exécuté
et les champs envoyés en POST.

```
afficherNomUtilisateur			-->			recupNomUtilisateur.php
afficherChamps					-->			recupNumChamps.php
afficherIlots					-->			recupNumIlots.php
afficherInfosChamp				-->			recupInfosChamp.php
afficherMoyennes				-->			recupMoyennes.php
afficherMesuresChamp			-->			recupMesuresChamp.php
afficherMesuresIlot				-->			recupMesuresIlot.php
```