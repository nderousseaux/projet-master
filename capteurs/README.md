# Capteurs
## Programme
### Docker
#### Programme de mesures
Construire l'image Docker :  
`docker build --build-arg PROG_TYPE=mesures -t capteurs:latest`

#### Programme d'agrégation
Construire l'image Docker :  
`docker build --build-arg PROG_TYPE=agregateur -t agregateur:latest`

#### Utilisation
Lancer l'image créé :  
`docker run -it -d nomImage:latest`

S'attacher au container :  
`docker attach IdDuContainer`

---
### Manuellement
Pour simplifier la compilation, il suffit de lancer le script `./build.sh`.  
Il est nécessaire d'indiquer un paramètre, afin de spécifier quel programme
compiler :

**Tous les programmes**
`./build.sh all`

**Programme de mesures :**  
`./build.sh mesures`

**Programme d'agrégation :**  
`./build.sh agregateur`

**Nettoyage du projet :**  
`./build.sh clean`

---
### Usage
#### Programme de mesures
Une fois compilé, un fichier `mesures` est créé dans le dossier `bin` et
peut être lancé avec les arguments suivants : `<id agri>` `<id champ>`
`<id ilot>` `<simuler>`.
Il peut également être lancé sans arguments, grâce au fichier `arguments.txt`,
se trouvant le dossier `capteurs/configuration'.
Un fichier est déjà donné, où il suffit de modifier les champs ne commencants
pas par `#`.

Une constante, nommée `DEBUG`, se trouvant dans le fichier 'inc/main.h', permet
d'afficher des informations supplémentaires dans la sortie standard.

Les données sont enregistrées dans le dossier `stockage`, dans un fichier
nommé `mesures_idAgriIdChampIdIlot.txt`, sous
la forme :  
```
YYYY-MM-DD HH:MM:SS (UTC);idChamp [int];idIlot [int];idCapteur [int]; temp [float];humi [float];lumi [int]
```

Exemple :
`2023-12-11 15:05:43;4;2;1;22.5883;23.1385;105807`

#### Programme d'agrégation
Une fois compilé, un fichier `agregateur` est créé dans le dossier `bin` et
peut être lancé.
Il agrégera les mesures des bases de données présentes dans le dossier
`stockage`, dans un nouveau fichier nommés
`agrege_nombreAleatoire_horodatage.txt` (afin de le rendre unique).

Le programme se charge d'envoyer le fichier sur le serveur SFTP.  
Pour cela, un fichier stockant les identifiants SFTP est nécessaire.
Il doit se trouver dans le dossier `capteurs/configuration` et se nommer
`identifiantsSFTP.txt`.  
Un fichier est déjà donné, où il suffit de modifier les champs ne commencants
pas par `#`.