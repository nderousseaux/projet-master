# Conf Gateway 

Faire le [tuto noeud](../Noeuds/README.md) d'abord

## Creating the gateway

[Tuto](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/ROUTE.md#creating-the-gateway)

The gateway will be the DHCP server for the mesh network.
The instructions use the following network details:
    Network 192.168.199.x
    netmask 255.255.255.0
    gateway address 192.168.199.1

Install the DHCP software with command : 
```bash
sudo apt-get install -y dnsmasq
```

Configure the DHCP server by editing the dnsmasq.conf file as root user:
```bash
sudo nano /etc/dnsmasq.conf
```
and add the following lines to the end of the file:

    interface=bat0
    dhcp-range=192.168.199.2,192.168.199.99,255.255.255.0,12h

Change the startup file to add the routing rules to forward mesh traffic to the home/office network and do the Network Address Translation on the reply. Set the node as a mesh gateway and also configure the gateway interface IP address. To do this update the __start-batman-adv.sh__ file and change the content to:

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

Notice that:

    eth0 has an IP address on your home/office network
    bat0 has IP address 192.168.199.1
    wlan0 has no IP address assigned


- iwconfig to show the wireless interfaces on the device.
- sudo batctl if to show the interfaces participating in the mesh.
- sudo batctl n to show the neighbouring mesh nodes your gateway node can see. 
