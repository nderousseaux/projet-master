import mysql.connector
import random
import time

# Connect to the MySQL database
cnx = mysql.connector.connect(
    host="localhost",
    user="monitoring",
    password="dPI!uiX9*eRm",
    database="monitoring"
)

# Create a cursor object to execute SQL queries
cursor = cnx.cursor()

# List all row of metrics in the database
query = "SELECT * FROM metrique"
cursor.execute(query)
metrics = cursor.fetchall()

# List all components in the database
query = "SELECT * FROM composant"
cursor.execute(query)
components = cursor.fetchall()

# Each 10 seconds, insert a new data entry

while True:
	# for each metric and component
	for metric in metrics:
			for component in components:
					query = "INSERT INTO log (valeur, composant_id, metrique_id) VALUES (%s, %s, %s)"
					if metric[1] == "Up":
						# Random value between 0 and 1 (boolean)
						value = random.randint(0, 1)
					else:
						# Random value between 0 and 100 (decimal)
						value = random.randint(0, 100) + random.random()
					data = (value, component[0], metric[0])
					cursor.execute(query, data)
					cnx.commit()
	print("Data inserted")
	time.sleep(10)

# Close the cursor and connection
cursor.close()
cnx.close()
