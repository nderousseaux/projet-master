# Config

## Flasher l'image

```bash
sudo apt install rpi-imager
```

## Logs

hostname : pi  
Mot de passe : tprli

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