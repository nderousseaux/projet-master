# Capteurs
## Compilation
Pour simplifier la compilation, il suffit de lancer le script `./build.sh`.

Les fichiers générés peuvent être nettoyés grâce à la commande
`./build.sh clean`.

## Usage
Une fois compilé, un fichier `recupMesures` est créé et peut être lancé, avec
les arguments suivants : `<id champ>` `<id ilot>` `<id capteur>`.

Une constante, nommée `DEBUG`, se trouvant dans le fichier 'inc/main.h', permet
d'afficher des informations supplémentaires dans la sortie standard.