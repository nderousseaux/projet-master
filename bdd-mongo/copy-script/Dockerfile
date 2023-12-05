FROM ubuntu:20.04

COPY requirements.txt requirements.txt
COPY copy-into-db.py copy-into-db.py

RUN apt-get update && apt install -y python3 pip
RUN pip install -r requirements.txt

ENTRYPOINT ["tail", "-f", "/dev/null"]
