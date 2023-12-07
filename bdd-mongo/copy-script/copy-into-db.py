import pymongo
from datetime import datetime
import sys

def parse_line(line):
    parts = line.strip().split(';')
    date_str = parts[0]
    date_time = datetime.strptime(date_str, '%Y-%m-%d %H:%M:%S')
    id_agri, id_champ, id_ilot = map(int, parts[1:4])
    temp, humi= map(float, parts[4:6])
    lumi = int(parts[6])

    temp_entry = {
        'datetime': date_time,
        'idAgri': id_agri,
        'idChamps': id_champ,
        'idIlot': id_ilot,
        'temp': temp,
    }

    humi_entry = {
        'datetime': date_time,
        'idAgri': id_agri,
        'idChamps': id_champ,
        'idIlot': id_ilot,
        'humi': humi,
    }
    
    lumi_entry = {
        'datetime': date_time,
        'idAgri': id_agri,
        'idChamps': id_champ,
        'idIlot': id_ilot,
        'lumi': lumi,
    }

    return temp_entry, humi_entry, lumi_entry



if(len(sys.argv) == 1):
    print("Please specify the file path as cli argument.")
    exit(-1)

client = pymongo.MongoClient(
        "mongodb://mongo1:30001/",
    replicaset="rs0",
    serverSelectionTimeoutMS=5000
)

db = client['data']

file_path = sys.argv[1]
with open(file_path, 'r') as file:
    for line in file:
        temp, humi, lumi = parse_line(line)
        db['temp'].insert_one(temp)
        db['humi'].insert_one(humi)
        db['lumi'].insert_one(lumi)

client.close()

