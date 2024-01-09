# VPN

## OpenVON
### Mise en place Général
#### Installation
- Installation OpenVPN
```
sudo apt update
sudo apt install openvpn
```
- Configuration de de l'heure en fonction de la zone *(éventuellement inutile du à la configuration lors de l'installation)*
```
sudo apt update
sudo apt -y install tzdata
dpkg-reconfigure tzdata
```

#### Configuration des clés
```
sudo mkdir /etc/openvpn/easy-rsa
sudo cp -r /usr/share/easy-rsa/* /etc/openvpn/easy-rsa/
cd /etc/openvpn/easy-rsa
sudo ./easyrsa init-pki 
sudo ./easyrsa build-ca
# Common name: gateway
sudo ./easyrsa gen-dh
sudo ./easyrsa gen-req gateway nopass
# Common name: gateway
sudo ./easyrsa sign server [Common name]
# Dans l'installation ci-dessus 'server' était le common name malgré les manip
```
* `init-pki`
* * création de clé publique
* * PKI structure qui gère, création + distribution + révocation + expiration des certificats numétique
* * init PKI créé répertoires + fichier nécessaire pour stocker éléments de la PKI
* `build-ca`
* * CA = autorité de certification, délivre les certificats numériques
* * init PKI = génération 2 clés (pulbique & privé) pour CA
* `gen-dh`
* * Génère différent paramètres **Diffie-Hellman** (DH) utile dans processus d'échange de clés
* * * permet la création de génération de clé de session commune
* * * param peuvent être renouvelé périodiquement pour augmenter la sécurité
* * Création clé PKI
* `gen-req gateway nopass`
* * Génère une demande de certificat (CSR, Certificate Signing Request)
* * param `gateway` spécifie le nom pour qui on génère la demande
* * param `nopass` pas besoin d'entrer un mp manuellement
* `sign server`

### Génération des clés de certificats Client
```
cd /etc/openvpn/easy-rsa
sudo ./easyrsa gen-req rpi nopass
```




## WireGuard

### WireGuard Server
Plus d'information sur le site suivant: https://docs.pi-hole.net/guides/vpn/wireguard/server/

#### Installation et Setup
Installation
```
sudo apt-get install wireguard wireguard-tools
```

Mise en place
```
sudo -i
cd /etc/wireguard
unmask 077
wg genkey | tee server.key | wg pubkey > server.pub
sudo nano /etc/wireguard/wg0.conf

# Copy it in /etc/wireguard/wg0.conf
[Interface]
Address = 10.100.0.1/24, fd08:4711::1/64
ListenPort = 47111

echo "PrivateKey = $(cat server.key)" >> /etc/wireguard/wg0.conf
exit # Exit super user mode
```
#### Démarage server
Enregistrer le server sour le nom de wg0
```
sudo systemctl enable wg-quick@wg0.service
sudo systemctl daemon-reload
sudo systemctl start wg-quick@wg0
```

Vérification du bon lancement du VON
```
sudo wg
```
Une sortie équivalente à celle-ci sera affiché
```
public key: MaSuperCleTresLongueFinissantParUn=
private key: (hidden)
listening port: 47111
```

### WireGuard client
Plus d'information sur le site suivant: https://docs.pi-hole.net/guides/vpn/wireguard/client/

#### Installation et Setup
Installation
```
sudo apt-get install wireguard wireguard-tools
```

Mise en place et génération de la paire de clé
```
sudo -i
cd /etc/wireguard
umask 077
name="client_name"
wg genkey | tee "${name}.key" | wg pubkey > "${name}.pub"
```

Il possible de générer des PSK KEY supplémentaire pour ajouter une couche à la cryptographe 
```
wg genpsk > "${name}.psk"
```

#### Ajout du nouveau client
```
echo "[Peer]" >> /etc/wireguard/wg0.conf
echo "PublicKey = $(cat "${name}.pub")" >> /etc/wireguard/wg0.conf
echo "PresharedKey = $(cat "${name}.psk")" >> /etc/wireguard/wg0.conf
echo "AllowedIPs = 10.100.0.2/32, fd08:4711::2/128" >> /etc/wireguard/wg0.conf
```

```
#! /usr/bin/env bash
umask 077

ipv4="$1$4"
ipv6="$2$4"
serv4="${1}1"
serv6="${2}1"
target="$3"
name="$5"

wg genkey | tee "${name}.key" | wg pubkey > "${name}.pub"
wg genpsk > "${name}.psk"

echo "# $name" >> /etc/wireguard/wg0.conf
echo "[Peer]" >> /etc/wireguard/wg0.conf
echo "PublicKey = $(cat "${name}.pub")" >> /etc/wireguard/wg0.conf
echo "PresharedKey = $(cat "${name}.psk")" >> /etc/wireguard/wg0.conf
echo "AllowedIPs = $ipv4/32, $ipv6/128" >> /etc/wireguard/wg0.conf
echo "" >> /etc/wireguard/wg0.conf

echo "[Interface]" > "${name}.conf"
echo "Address = $ipv4/32, $ipv6/128" >> "${name}.conf"
echo "DNS = ${serv4}, ${serv6}" >> "${name}.conf" #Specifying DNS Server
echo "PrivateKey = $(cat "${name}.key")" >> "${name}.conf"
echo "" >> "${name}.conf"
echo "[Peer]" >> "${name}.conf"
echo "PublicKey = $(cat server.pub)" >> "${name}.conf"
echo "PresharedKey = $(cat "${name}.psk")" >> "${name}.conf"
echo "Endpoint = $target" >> "${name}.conf"
echo "AllowedIPs = ${serv4}/32, ${serv6}/128" >> "${name}.conf" # clients isolated from one another
# echo "AllowedIPs = ${1}0/24, ${2}/64" >> "${name}.conf" # clients can see each other
echo "PersistentKeepalive = 25" >> "${name}.conf"

# Print QR code scanable by the Wireguard mobile app on screen
qrencode -t ansiutf8 < "${name}.conf"

wg syncconf wg0 <(wg-quick strip wg0)
```
Commande a exécuter
