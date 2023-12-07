# Désactiver/activer l'interface wifi
> sudo ip link set dev wlan0 down
Activer l'interface wifi
sudo ip link set dev wlan0 up

# Node
> sudo apt-get install -y batctl
> nano ~/start-batman-adv.sh
```
#!/bin/bash
# batman-adv interface to use
sudo batctl if add wlan0
sudo ifconfig bat0 mtu 1468

# Tell batman-adv this is a gateway client
sudo batctl gw_mode client

# Activates batman-adv interfaces
sudo ifconfig wlan0 up
sudo ifconfig bat0 up
```
> chmod +x ~/start-batman-adv.sh
> sudo nano /etc/network/interfaces.d/wlan0
```
auto wlan0
iface wlan0 inet manual
    wireless-channel 1
    wireless-essid call-code-mesh
    wireless-mode ad-hoc
```
> echo 'batman-adv' | sudo tee --append /etc/modules
> echo 'denyinterfaces wlan0' | sudo tee --append /etc/dhcpcd.conf
> sudo nano /etc/rc.local
```before exit 0
/home/capt2/start-batman-adv.sh &
```


# Gateway
> sudo apt-get install -y dnsmasq
> sudo nano /etc/dnsmasq.conf
```add end of file
interface=bat0
dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h
```
sudo nano ~/start-batman-adv.sh
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
# Code Générer des données
## Envoyer périodiquement des données
crontab -e
*/2 * * * * /path/prog

================================
# TODO
Etablir connection avec RPI et PC directement connecté par Ethernet sans passé par le wifi