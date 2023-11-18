# Backend

## Appels des fonctions PHP
*Les fonctions JS appelé dans index.html sont résumé à la fin du document index.html. Le code des fonctions JS se trouve dans site > backend > scipt > afficherDonnes.js.*
*La fonction qui envoie les requêtes se trouve dans recupDonnees.js. Elle permet de généraliser les requêtes en un simple appel de fonction ayant pour paramètre le nom du script php qui sera exécuté.*

```
afficherNomUtilisateur          -->         recupNumUtilisateur.php
afficherChamps                  -->         recupNumChamps.php
afficherIlots                   -->         recupNumIlots.php
afficherInfosChamp              -->         recupInfosChamp.php
afficherMoyennes                -->         recupMoyennes.php
afficherTableauToutesMesures    -->         recupMesuresChamp.php
recupAbsOrdGraph (graphique.js) -->         recupMesuresIlot.php
```