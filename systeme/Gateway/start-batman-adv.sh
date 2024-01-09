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

# Mise en place de wlan0
sudo ifconfig wlan0 down
sudo rfkill unblock wifi; sudo rfkill unblock all

sudo iwconfig wlan0 mode ad-hoc
sudo iwconfig wlan0 essid call-code-mesh
sudo iwconfig wlan0 channel 1

sudo ifconfig wlan0 10.0.1.1/24

# Activates batman-adv interfaces
sudo ifconfig wlan0 up
sudo ifconfig bat0 up
sudo ifconfig bat0 192.168.199.1/24
