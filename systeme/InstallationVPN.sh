# Installation des programmes requis
sudo apt update
sudo apt install openvpn

# Configuration VPN
sudo mkdir /etc/openvpn/easy-rsa
sudo cp -r /user/share/easy-rsa/* /etc/openvpn/easy-rsa
cd /etc/openvpn/easy-rsa