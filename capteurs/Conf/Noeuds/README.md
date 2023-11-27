# Configuration d'un noeud 
[Tuto](https://github.com/binnes/WiFiMeshRaspberryPi/blob/master/part1/PIMESH.md#setup-batman-adv)

## Batman-adv
[Batman-adc wiki](https://www.open-mesh.org/projects/open-mesh/wiki)

Such an instance is a virtual network interface (often called "bat0") which looks to the system like a switch port that allows access to the distributed switch. The actual details of the (direct or indirect) communication between the nodes is then hidden behind the "bat0" network interface.

![Image](Images/bat0.png)



Nous allons configurer le module noyau batman-adv pour prendre le contrôle de l'interface WiFi wlan0 et créer un réseau maillé sur WiFi. Batman-adv créera ensuite une nouvelle interface bat0 pour permettre au Pi d'envoyer du trafic réseau sur le réseau 

## Mise en place du noeud

To manage the mesh network, a utility called batctl needs to be installed. This can be done using command
```bash
sudo apt-get install -y batctl
```
Using your preferred editor create a file ~/start-batman-adv.sh
```bash
nano ~/start-batman-adv.sh
```

Make the start-batman-adv.sh file executable with command :
```bash
chmod +x ~/start-batman-adv.sh
```
Create the network interface definition for the wlan0 interface by creating a file as root user e.g.
```bash
sudo nano /etc/network/interfaces.d/wlan0
```

Ensure the batman-adv kernel module is loaded at boot time by issuing the following command :
```bash
echo 'batman-adv' | sudo tee --append /etc/modules
```

Stop the DHCP process from trying to manage the wireless lan interface by issuing the following command :
```bash
echo 'denyinterfaces wlan0' | sudo tee --append /etc/dhcpcd.conf
```

Make sure the startup script gets called by editing file /etc/rc.local as root user, e.g.
```bash
sudo nano /etc/rc.local
```
and insert:  

    /home/pi/start-batman-adv.sh &

before the last line: exit 0

