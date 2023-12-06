# Conf Gateway 

Le script Build_gateway.sh -> Met en place le gateway

## Creating the gateway

[Tuto](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/ROUTE.md#creating-the-gateway)

La gateway fera office de serveur DHCP.
Le gateway utilisera les paramettres réseau suivant :  
Pour l'interface bat0 :
    Network 192.168.199.x
    netmask 255.255.255.0
    gateway address 192.168.199.1

Sous réseau entre les capteurs :
    Network 10.0.1.x
    netmask 255.255.255.0

Install the DHCP software with command : 
```bash
sudo apt-get install -y dnsmasq
```

Configurez le serveur DHCP en configurant le fichier suivant en tant que root :
```bash
sudo nano /etc/dnsmasq.conf
```
et ajouter les lignes suivantes à la fin du fichier :

    interface=bat0
    dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h

    interface=wlan0
    dhcp-range=10.0.1.2,10.0.1.255,255.255.255.0,12h

Modifiez le fichier de démarrage (__start-batman-adv.sh__) en ajoutant les règles de routage nécessaires pour diriger le trafic maillé vers le réseau domestique/de bureau. Assurez-vous d'effectuer la traduction d'adresse réseau sur la réponse. Définissez le nœud en tant que passerelle maillée et configurez l'adresse IP de l'interface de la passerelle.  
Les modifications à apporter au contenu du fichier sont les suivantes :

```
#!/bin/bash
# batman-adv interface to use
sudo batctl if add wlan0
sudo ifconfig bat0 mtu 1468

# Tell batman-adv this is an internet gateway
sudo batctl gw_mode server

# Enable port forwarding
sudo sysctl -w net.ipv4.ip_forward=1
sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
sudo iptables -A FORWARD -i eth0 -o bat0 -m conntrack --ctstate RELATED,ESTABLISHED -j ACCEPT
sudo iptables -A FORWARD -i bat0 -o eth0 -j ACCEPT

# Activates batman-adv interfaces
sudo ifconfig wlan0 up
sudo ifconfig bat0 up
sudo ifconfig bat0 192.168.199.1/24
```

### Remarque

[Verify the gateway](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/ROUTE.md#verifying-the-gateway)

- sudo batctl if : Pour afficher les interfaces qui participent au mesh network
- sudo batctl n : Pour afficher les voisins qui participent au mesh que le gateway peut voir.

## Bibliographie 
[tuto noeud](../Noeuds/README.md)