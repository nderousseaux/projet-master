# Coap

[Coapthon](https://github.com/Tanganelli/CoAPthon)

## Installation 

```bash
# TODO Lister les commandes à taper pour installer Coapthon3
sudo apt install pip
sudo pip install CoAPthon
git clone git@github.com:Tanganelli/CoAPthon3.git
cd CoAPthon3
python3 setup.py sdist
sudo pip install dist/CoAPthon3-1.0.1+fb.202312141023.tar.gz -r requirements.txt
```

## Serveur 

```bash
python3 oui_coapserveur.py -i 127.0.0.1 -p 5683
```

## Client 

```bash
python3 coapclient.py -o GET -p coap://127.0.0.1:5683/basic

python3 coapclient.py -o POST -p coap://127.0.0.1:5683/basic -f test.txt

```