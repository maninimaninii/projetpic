<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>yesyes</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        
        body {
            background-color: #333;  
        }
    </style>
</head>
<body>

<div>
    <canvas id="myChart" width="400" height="200"></canvas>
</div>

<script>

    var data = {
        labels: [],
        datasets: [{
            label: 'Distances Récupérées',
            backgroundColor: 'white',
            borderColor: 'gold', 
            borderWidth: 1,
            data: []
        }]
    };

    var options = {
        scales: {
            y: {
                ticks: {
                    color : 'white'
                },
                beginAtZero: true,
                color :'white',
                max: 300
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: 'white',
                    font: {
                        size: 30
                    } 
                }
            }
        },
        layout: {
            padding: {
                left: 10,
                right: 10,
                top: 10,
                bottom: 60
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

    function actualiserGraphique() {
    $.ajax({
        url: 'generate.php',
        type: 'GET',
        dataType: 'json',
        data: { last_id: lastId }, // Envoyer le dernier ID récupéré
        success: function(response) {
            if (response.distance !== null) {
                // Ajouter une seule valeur récupérée depuis la base de données au graphique
                data.labels.push(''); // Ajouter une étiquette vide (ou une étiquette appropriée si nécessaire)
                data.datasets[0].data.push(response.distance);

                // Limiter le nombre de points affichés à une certaine quantité (par exemple, 10)
                if (data.labels.length > 10) {
                    data.labels.shift(); // Supprimer la première étiquette
                    data.datasets[0].data.shift(); // Supprimer la première valeur
                }

                // Mettre à jour le graphique
                myChart.update();

                // Mettre à jour le dernier ID récupéré
                lastId = response.new_id;
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur lors de la récupération des données :", error);
        }
    });
}

// Déclarer lastId en dehors de la fonction actualiserGraphique
var lastId = 0;

// Actualiser le graphique toutes les 2 secondes (2000 ms)
setInterval(actualiserGraphique, 1000);

</script>

</body>
</html>
