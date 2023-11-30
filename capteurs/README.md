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
peut être lancé, avec les arguments suivants : `<id champ>` `<id ilot>`
`<id capteur>`.

Une constante, nommée `DEBUG`, se trouvant dans le fichier 'inc/main.h', permet
d'afficher des informations supplémentaires dans la sortie standard.


Un fichier pour stocker les identifiants SFTP est nécessaire. Il doit se trouver
dans le dossier `capteurs` et se nommer `identifiantsSFTP.txt`.  
Il doit comporter les champs suivants, dans l'ordre :  
```
IPServeur
nomUtilisateur
motDePasse
```

#### Programme d'agrégation
Une fois compilé, un fichier `agregateur` est créé dans le dossier `bin` et
peut être lancé.
Il agrégera les mesures des bases de données présentes dans le dossier `bdd`,
dans une nouvelle base de données, dont le nom est défini dans la constante
`NOM_BDD_AGR`, dans le fichier `inc/agregateur/agregateurBdd.h`.