#!/bin/bash

while true; do
	result=$(find /data/sftp_user/upload/ -type f -mmin -2)
	if [ -n "$result" ]; then
		cp $result /pm/bdd-mongo/copy-script/donnees/donnees.txt
		docker exec python python3 copy-into-db.py /data/donnees.txt
	fi
	sleep 120
done
