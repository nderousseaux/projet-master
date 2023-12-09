# Config

## Préambule

Vérifier les connectiques des différents composants. En effet une connection d'un câble ou micro carte SD peut amener à un arrêt de boot pas complet. *Ex: début de boot puis arrêt vers la fin*.

## Flasher l'image

Si un ancien système est déjà installer ou qu'il reste des données. Veuillez faire une sauvegarde des données sur un autre disque. Ensuite faite un formatage avec ré-écriture pour être sûr qu'il ne reste aucune données parasite.

OS utilisé: `RASPBERRY PI OS (LEGAGY, 64-BITS) LITE`

```bash
sudo apt install rpi-imager
```

Une fois le rpi-imager ouvert vous devez sélectionner l'OS (ci-dessus), sélectionner la carte SD puis vous pouvez ajouter les options.
Dans les options vous pouvez configurer le mot de passe, la connection WIFI, ssh, clavier etc... Nous vous recommandons de préfdéfinir les paramètres pour simplifier l'installation. (*Attention tout les OS ne permettent par l'enregistrement de la configuration*)
Même si la wifi sera modifier par la suite. Il est préférable d'avoir une connection wifi fonctionnel pour mettre à jour les fichiers.

## Logs

Exemple de nommage
hostname : pi  
Mot de passe : tprli

*TODO définir un nommage pour le réseau*

## /etc/network/interfaces.d/wlan0

On peut remplacer le channel par un numéro de channel [ici](https://en.wikipedia.org/wiki/List_of_WLAN_channels)
Ici nous sommes sur le channel 1 (Europe)

**Attention** : Une fois l'interface modifiée, la RPI n'a plus de Wi-Fi. 

## Mise en place du noeud

[Documentation](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/PIMESH.md#setup-batman-adv)

## Mise en place du gateway

Faire le tuto du dessus, puis faire celui [là](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/ROUTE.md#creating-the-gateway)

[Pour lancer le gateway](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/ROUTE.md#boot-the-mesh-network)

## à l'allumage

```bash
sudo apt update
sudo apt upgrade

# Pour faire fonctionner le script test :
sudo apt install cmake
sudo apt install libssh2-1-dev
sudo apt-get install libsqlite3-dev
```

## Remarque

```bash
# Sur la RPI avec l'alim blanche ne pas faire :
sudo apt update 
sudo apt upgrade
# Sinon la RPI une fois éteinte, ne s'allume plus.
```