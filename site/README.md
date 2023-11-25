# Site
## Docker
Construire l'image Docker :  
`docker build -t site:latest`

Lancer l'image créé :  
`docker run -it -p 8080:80 -d site:latest`

Ouvrir un navigateur Internet et accéder à l'adresse :  
`localhost:8080`

S'attacher au container :  
`docker attach IdDuContainer`

## Manuellement
Afin de faire fonctionner le backend, il est nécessaire de lancer PHP, depuis
le dossier `site/`.

Pour cela, il faut lancer la commande :  
`php -S localhost:8080`

Puis, depuis un navigateur Internet, ouvrir `localhost:8080`.