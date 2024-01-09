#!/bin/bash 

INTERFACE=lo
PORT=5683
IP=127.0.0.1

IN=/etc/coap/in.txt

sudo python3 coapserveur.py -i $IP -p $PORT -f $IN