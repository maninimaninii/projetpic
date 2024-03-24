import serial
import mysql.connector
import csv

# Ouverture de la connexion UART
ser = serial.Serial('COM6', 9600, timeout=1)

# Configuration de la connexion à la base de données
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'pic',
}

# Création de la connexion à la base de données
conn = mysql.connector.connect(**db_config)

# Création d'un objet curseur pour exécuter les requêtes SQL
cursor = conn.cursor()

# Spécifiez le chemin complet du fichier
backup_filepath = 'C:\\Users\\Manny\\AndroidStudioProjects\\Bluet\\app\\src\\main\\assets\\data.txt'

try:
    # Effacer le contenu du fichier de sauvegarde
    open(backup_filepath, 'w').close()

    delete_query = "DELETE FROM donnee"
    cursor.execute(delete_query)
    conn.commit()
    while True:
        # Lecture d'un octet depuis l'UART
        byte_data = ser.read(1)

        # Vérification si la lecture est vide
        if not byte_data:
            break  # Sortir de la boucle si aucune donnée n'est lue

        # Conversion du caractère hexadécimal en décimal
        decimal_value = int(byte_data.hex(), 16)
        print(f'Décimal: {decimal_value}')

        # Insérer la valeur décimale dans la base de données
        insert_query = "INSERT INTO donnee (distance) VALUES (%s)"
        cursor.execute(insert_query, (decimal_value,))
        conn.commit()

        # Sauvegarder la valeur dans le fichier spécifié
        with open(backup_filepath, 'a', newline='') as csvfile:
            csv_writer = csv.writer(csvfile)
            csv_writer.writerow([decimal_value])

except KeyboardInterrupt:
    # Fermeture de la connexion lorsqu'on appuie sur Ctrl+C
    ser.close()
    print('Connexion UART fermée.')
    
    # Fermeture de la connexion à la base de données
    cursor.close()
    conn.close()
