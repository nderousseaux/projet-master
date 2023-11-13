# Capteurs
## Compilation
Pour simplifier la compilation, il suffit de lancer le script `./build.sh`.

Les fichiers générés peuvent être nettoyés grâce à la commande
`./build.sh clean`.

## Usage
### Programme de mesures
Une fois compilé, un fichier `mesures` est créé dans le dossier `bin` et
peut être lancé, avec les arguments suivants : `<id champ>` `<id ilot>`
`<id capteur>`.

Une constante, nommée `DEBUG`, se trouvant dans le fichier 'inc/main.h', permet
d'afficher des informations supplémentaires dans la sortie standard.

### Programme d'agrégation
Une fois compilé, un fichier `agregateur` est créé dans le dossier `bin` et
peut être lancé.
Il agrégera les mesures des bases de données présentes dans le dossier `bdd`,
dans une nouvelle base de données, dont le nom est défini dans la constante
`NOM_BDD_AGR`, dans le fichier `inc/agregateur/agregateurBdd.h`.