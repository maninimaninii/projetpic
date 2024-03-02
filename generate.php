<?php

// paramètres de co à la BD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pic";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requête SQL pour récupérer les distances
$sql = "SELECT distance FROM donnee";

$result = $conn->query($sql);


if ($result) {
    // Récupérer les distances 
    $distances = [];
    while ($row = $result->fetch_assoc()) {
        $distances[] = $row['distance'];
    }

    // Fermer la connexion à la base de données
    $conn->close();

    // Envoyer les distances en tant que réponse
    echo json_encode($distances);
} else {
    
    echo "Erreur SQL : " . $conn->error;
}

?>
