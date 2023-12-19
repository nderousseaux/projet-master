# Security

## Préambule
Ce document Markedown contient:
- un résumé des différentes sécurité mise en place pour différentes failles
- les différentes possible faille de sécurité
- les maintenances à effectué pour conserver un bon niveau de sécurité

## Point sécurisé
### Transmission des messages
Pour communiquer les données collectés au gateway on utilise le protocole sftp. Ce protocole offre une encryption de ces messages spécifiques.

### Visibilité du réseaux
#### De l'extérieur
Actuellement ce qui s'épare le réseau mesh du restant de l'internet c'est le gateway. Depuis l'internet nous n'avons pas de visibilité sur l'infrastructure du réseau mesh. Pour pouvoir le voir il faut se connecter en ssh au gateway et executer les commandes depuis celui-ci.

[TODO] L'intérieur du réseau mesh ne devrait pas non plus pouvoir voir internet. Ainsi si une faille est découverte dans le réseau mesh l'attaquant à l'intérieur du mesh ne pourra pas dépasser le gateway.

## Maintenance
### VPN
Pour maintenir une certaine sécurité il est important de renouvelé les paramètre **Diffie-Hellman** à l'aide de la commande `easyrsa gen-dh`. La mise à jour régulière des paramètres réduit le faille.

Le certificat générer est valable 825 jours. Pour des raisons de sécurité et par précaution. Il sera demander de renouveler les certificats chaque année.