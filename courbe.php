<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>yesyes</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div>
    <canvas id="myChart" width="400" height="200"></canvas>
</div>

<script>

    var data = {
        labels: [],
        datasets: [{
            label: 'Nombres aléatoires',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            data: []
        }]
    };

    var options = {
        scales: {
            y: {
                beginAtZero: true,
                max: 300
            }
        }
    };

    // Créer 
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: options
    });

    
  
    function actualiserGraphique(distances) {
        // Effacer les données actuelles
        data.labels = [];
        data.datasets[0].data = [];

        // Ajouter les distances aux données
        distances.forEach(function(distance) {
            data.labels.push('');
            data.datasets[0].data.push(distance);
        });

        // Mettre à jour le graphique
        myChart.update();
    }

    function actualiserContenu() {
        $.ajax({
            url: 'generate.php',
            type: 'GET',
            dataType: 'json', // Indiquer que le serveur renvoie du JSON
            success: function(data) {
                // Mettre à jour le graphique avec les distances récupérées
                actualiserGraphique(data);
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de la récupération des données :", error);
            }
        });
    }


    //  toutes les secondes (1000 ms)
    setInterval(actualiserContenu, 200);
</script>

</body>
</html>
