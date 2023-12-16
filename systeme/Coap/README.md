# Coap

[Coapthon](https://github.com/Tanganelli/CoAPthon)

## Installation 

```bash
# TODO Lister les commandes à taper pour installer Coapthon3
```

## Serveur 

```bash
python3 oui_coapserveur.py -i 127.0.0.1 -p 5683
```

## Client 

```bash
python3 oui_coapclient.py -o GET -p coap://127.0.0.1:5683/basic

python3 oui_coapclient.py -o POST -p coap://127.0.0.1:5683/basic -f test.txt

```