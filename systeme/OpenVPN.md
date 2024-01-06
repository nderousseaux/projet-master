# OpenVPN

## Mise en place Général
### Installation
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

### Configuration des clés
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

## Génération des clés de certificats Client
```
cd /etc/openvpn/easy-rsa
sudo ./easyrsa gen-req rpi nopass

```