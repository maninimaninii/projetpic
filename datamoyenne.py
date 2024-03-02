import serial
import mysql.connector

# Ouverture de la connexion UART
ser = serial.Serial('COM6', 9600, timeout=1)

# Paramètres de co à  BD
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'pic',
}

# Connnexion
conn = mysql.connector.connect(**db_config)

# cursor pour exec requetes sql
cursor = conn.cursor()

try:
    # liste pour stocker cinq dernieres distances
    cinq = []

    while True:
        # Lecture d'un octet depuis l'UART
        byte_data = ser.read(1)

    
        if not byte_data:
            break  # Sortir de la boucle si aucune donnée n'est lue

        # Conversion du caractère hexadécimal en décimal
        decimal_value = int(byte_data.hex(), 16)
        print(f'Décimal: {decimal_value}')

    
        cinq.append(decimal_value)

        # Garder seulement les cinq dernières distances dans la liste
        if len(cinq) > 5:
            # moyenne des cinq dernières distances
            moyenne = round(sum(cinq) / len(cinq))
            print(f'Moyenne des 5 dernières distances: {moyenne}')

            # Insérer la moyenne dans la base de données
            insert_query = "INSERT INTO donnee (distance) VALUES (%s)"
            cursor.execute(insert_query, (moyenne,))
            conn.commit()

            # Vider la liste
            cinq = []

except KeyboardInterrupt:
    # Fermeture de la connexion lorsqu'on appuie sur Ctrl+C
    ser.close()
    print('Connexion UART fermée.')
    # Fermeture de la connexion à la base de données
    cursor.close()
    conn.close()
