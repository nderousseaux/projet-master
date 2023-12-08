############### GEN_FILE #################
# Nécessite les installations suivante
# > sudo apt install python3-pip
# > sudo pip install pysftp
#
# Pour pouvoir utiliser ce programme, il faut faire 
# une première connexion sftp comme ceci:
# > sftp USER@HOSTNAME 
# Dans le cadre du projet:
# USER      = login
# HOSTNAME  = IP
# Le mot de passe sera demandé par la suite
#
# Après la première connexion vous pouvez appeler
# le script.
# > python3 gen_file.py
# Il sera demander les informations suivantes
# IP            : se sera l'IP comme pour la connexion sftp
# user          : login comme "..."
# password      : mot de passe de connexion comme "..."
# distant dir   : si le répertoire c'est ./tata/ écrire tata
# locale file   : si le fichier c'est ./toto.txt écrire toto
#
# Problème rencontrer:
# - Exec script --> No hostkey: vous vous êtes pas connecter en
# sftp avant l'execution du programme et le script ne peut pas 
# faire l'échange de clé. Il faut donc d'abord faire une connection
# classique en sftp
# - No file found --> le fichier par le script ne peut pas ne pas exister
# il est créé ou overwrite avant l'envoie sftp. Donc il s'agit probablement
# du répertoire distant. Pour vérifier vous pouvez effectuer une connexion sftp
# (tel qu'expliquer ci-dessus) puis faire un 'ls' pour vérifier l'existance
# du répertoire 
#
#####################################################

################## IMPORT ###########################
import datetime
import time
# IF NOT INSTALL
# sudo pip install pysftp
import pysftp

################### FUNCTION #########################

# Compose date and add 0 if single digit 
# ex: 2024-5-14 --> 2024-05-14
# Work on months and days
# obviously not for the year
def compose_date(year, month, day):
    year = str(year)

    if month < 10:
        month = "0"+str(month)
    else:
        month = str(month)

    if day < 10:
        day = "0"+str(day)
    else:
        month = str(day)

    return year+"-"+month+"-"+day

# Compose time and add 0 if single digit 
# ex: 1:21:41 --> 01:21.41
# work for hours, minutes, secondes
def compose_time(hour, min, sec):
    if hour < 10:
        hour = "0"+str(hour)
    else:
        hour = str(hour)        

    if min < 10:
        min = "0"+str(min)
    else:
        min = str(min)

    if sec < 10:
        sec = "0"+str(sec)
    else:
        sec = str(sec)
        
    return hour+":"+min+":"+sec

# Assemble a fake data line who is reable for the database
# format "date;idChamp;idIlot;idCapteur;temp;humi;lumi"
# ex: '2024-12-08 01:29:40;1;2;3;11:22;33'
# id & mesure are fake
def compose_text(date,hour,minute,seconde):
    # compose hour:minute:seconde
    t_time = compose_time(hour,minute,seconde)
    
    # assemble text
    text_time = t_day+" "+t_time
    text_to_send = text_time + ";0;0;1;10.0;5.0;20"

    return text_to_send

#################### PROGRAMMME #######################

print("START")

# get current time
current_time = datetime.datetime.now()
     
# get year     
year = current_time.year
# get month     
month = current_time.month
# get day
day = current_time.day
# compose year-month-day
t_day = compose_date(year,month,day)
# get hour
hour = current_time.hour
# get minute
minute = current_time.minute
# get seconde
seconde = current_time.second

# overwrite sec to 0 for experience
seconde = 0

print("SFTP CONNECT")

# SFTP connection info
hote = input('IP: ')
utilisateur = input('user: ')
mot_de_passe = input('password: ')
# Normalement /upload
chemin_sftp = input('distant dir: ')
# Nom du fichier qui va être créer
fichier_local = input('new file name: ')

# SFTP connection
with pysftp.Connection(hote, username=utilisateur, password=mot_de_passe) as sftp:
    try:
        # Changement de répertoire distant (si nécessaire)
        sftp.chdir(chemin_sftp)

        for i in  range(0, 5):
            # incremente time
            seconde = seconde + 1
            # compose text
            text_to_send = compose_text(t_day, hour, minute, seconde)

            # Print text
            print(text_to_send)
            # Write in file
            f = open(fichier_local, "w")
            f.write(text_to_send)
            f.close()
            # Téléchargement du fichier dans le répertoire spécifié
            sftp.put(fichier_local)
            # Delay 2 minutes
            time.sleep(120)

        print(f'File {fichier_local} upload in {chemin_sftp} on {hote}.')

        # Autres opérations avec la connexion ouverte	

    except Exception as e:
        # Gestion des erreurs
        print(f"AN ERROR OCCURS : {str(e)}")

print("SFTP DISCONNECT")

print("FINISH")
