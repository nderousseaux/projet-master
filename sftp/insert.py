# Ce programme lit tous les fichiers dans le dossier donné en argument (relatif ou absolu)
# et ajoute les entrees qui s'y trouvent dans la BdD Mongo.
# Ces entrees sont de la forme (1 par ligne):
#   date idAgri idChamps idIlot temperature humidite luminosite


import pymongo
from datetime import datetime
import sys
import os
import glob

import dotenv

dotenv.load_dotenv("/mongo.env")

# From mongo.env file
MONGO_URL = os.getenv("MONGODB_URL")

def parse_line(line):
    parts = line.strip().split(';')
    date_str = parts[0]
    date_time = datetime.strptime(date_str, '%Y-%m-%d %H:%M:%S')
    id_agri, id_champ, id_ilot = map(int, parts[1:4])
    temp, humi= map(str, parts[4:6])
    lumi = parts[6]

    temp_entry = {
        'date': date_time,
        'idAgri': id_agri,
        'idChamps': id_champ,
        'idIlot': id_ilot,
        'temp': temp,
    }

    humi_entry = {
        'date': date_time,
        'idAgri': id_agri,
        'idChamps': id_champ,
        'idIlot': id_ilot,
        'humi': humi,
    }
    
    lumi_entry = {
        'date': date_time,
        'idAgri': id_agri,
        'idChamps': id_champ,
        'idIlot': id_ilot,
        'lumi': lumi,
    }

    return [temp_entry, humi_entry, lumi_entry]



if(len(sys.argv) == 1):
    print("Please specify the folder path as cli argument.")
    exit(-1)

client = pymongo.MongoClient(
        MONGO_URL,
    replicaset="rs0",
    serverSelectionTimeoutMS=5000
)

db = client['data']

folder_path = sys.argv[1]
pattern = os.path.join(folder_path, '*')

for file_path in glob.glob(pattern):
    with open(file_path, 'r') as file:
        for line in file:
            new_entry = parse_line(line)
            if new_entry[0]["temp"] != "NaN":
                new_entry[0]["temp"] = float(new_entry[0]["temp"])
                db['temp'].insert_one(new_entry[0])
            if new_entry[1]["humi"] != "NaN":
                new_entry[1]["humi"] = float(new_entry[1]["humi"])
                db['humi'].insert_one(new_entry[1])
            if new_entry[2]["lumi"] != "NaN":
                new_entry[2]["lumi"] = int(new_entry[2]["lumi"])
                db['lumi'].insert_one(new_entry[2])

client.close()
