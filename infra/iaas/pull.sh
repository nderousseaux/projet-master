#!/bin/bash
while true; do
        echo "Pull en cours" | nc -l 3030
        curl -X POST 10.0.0.3/webhook/webhook.sh
        curl -X POST 10.0.0.5/webhook/webhook.sh
done
