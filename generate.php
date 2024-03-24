<?php

// Connexion à la base de données (à remplacer par vos propres informations de connexion)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pic";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer le dernier ID récupéré, par défaut 0 au début
$last_id = isset($_GET['last_id']) ? $_GET['last_id'] : 0;

// Requête SQL pour récupérer les distances avec un ID supérieur au dernier ID récupéré
$sql = "SELECT new_id, distance FROM donnee WHERE new_id > $last_id ORDER BY new_id ASC";

$result = $conn->query($sql);

// Vérifier si la requête a réussi
if ($result) {
    // Récupérer les données
    $data = $result->fetch_assoc();
    
    if ($data) {
        // Récupérer le nouvel ID et la distance
        $new_id = $data['new_id'];
        $distance = $data['distance'];

        // Retourner les données en tant que réponse JSON
        echo json_encode(array("new_id" => $new_id, "distance" => $distance));
    } else {
        // Aucune nouvelle donnée à récupérer
        echo json_encode(array("new_id" => $last_id, "distance" => null));
    }
} else {
    // En cas d'erreur SQL
    echo json_encode(array("error" => $conn->error));
}

// Fermer la connexion à la base de données
$conn->close();
?>
