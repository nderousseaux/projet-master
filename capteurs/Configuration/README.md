# Config

[IEEE 802.11s](https://en.wikipedia.org/wiki/IEEE_802.11s)

## Flasher l'image

```bash
sudo apt install rpi-imager
```

## Logs

hostname : pi  
Mot de passe : tprli

## Interface wifi ad hoc

"/etc/network/interfaces.d/wlan0"  
On peut remplacer le channel par un numéro de channel [ici](https://en.wikipedia.org/wiki/List_of_WLAN_channels)
Ici nous sommes sur le channel 1 (Europe)

**Attention** : Une fois l'interface modifiée, la RPI n'a plus de Wi-Fi. 

## Mise en place du noeud

- [Documentation](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/PIMESH.md#setup-batman-adv)
- [Description](Noeuds/README.md)

## Mise en place du gateway

[Tuto](Gateway/README.md)

## Pour que à Installer dans la RPI

```bash
sudo apt update
sudo apt upgrade

# Pour faire fonctionner le script test :
sudo apt install cmake
sudo apt install libssh2-1-dev
sudo apt-get install libsqlite3-dev

# Pour le réseau mesh :
sudo apt-get install -y batctl

# Seulement pour le Gateway :
sudo apt-get install -y dnsmasq

# Pour connecter et tester le traffic
sudo apt install npm
sudo npm install -g --unsafe-perm node-red
# sudo npm -g install npm (La derniere version n'est pas supporté par la RPI)
sudo npm -g install node-pre-gyp
sudo npm -g install node-gyp
```

## Remarque

```bash
# Sur la RPI avec l'alim blanche ne pas faire :
sudo apt update 
sudo apt upgrade
# Sinon la RPI une fois éteinte, ne s'allume plus.
```

