<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un concessionnaire
if (!isset($_SESSION['PROFILE']) || $_SESSION['PROFILE']['type'] !== 'g') {
    $_SESSION['message'] = "Vous devez être connecté en tant que garagiste pour accéder à cette page.";
    header('Location: connexion.php');
    exit();
}

$firstNameGaragiste = $_SESSION['PROFILE']['prenom'];
$lastNameGaragiste = $_SESSION['PROFILE']['nom'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un véhicule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <nav>
    <button class="btn btn-outline-secondary btn-lg" type="button" onclick="window.location.href='index_g.php'"><i class="bi bi-house"></i></button>
    </nav>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            margin: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .vehicle-info {
            margin: 20px;
        }
        .vehicle-info p {
            margin: 5px 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.1/dist/web3.min.js"></script>
    <script src="abi.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>
<body>
    
    <div class="container">
        <h1>Rechercher un véhicule</h1>
        <form id="searchForm">
            <label for="vin">Entrez le VIN :</label>
            <input type="text" id="vin" name="vin" required><br><br>
            <input type="submit" value="Rechercher">
        </form>
        <div class="row">
            <div class="col-md-6">
                <div class="vehicle-info" id="vehicleInfo">
                    <!-- Les informations du véhicule seront affichées ici -->
                </div>
            </div>
            <div class="col-md-6">
                <canvas id="kilometrageChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Initialiser web3
        if (typeof window.ethereum !== 'undefined' || typeof window.web3 !== 'undefined') {
            window.web3 = new Web3(window.ethereum || window.web3.currentProvider);
        } else {
            window.web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:7545'));
        }

        // Adresse du contrat CarRegistry
        const contractAddress = '0x8717270747e096c762C47d24aEEEB0dd4D1B64e5';
        const contract = new window.web3.eth.Contract(abi, contractAddress);
        let chart = null;

        // Gérer le formulaire de recherche
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const vin = document.getElementById('vin').value.trim();
            if (vin.length === 0) {
                alert('Veuillez entrer un VIN valide.');
                return;
            }
            clearPreviousResults();
            searchVehicle(vin);
        });

        // Fonction pour effacer les résultats précédents
        function clearPreviousResults() {
            document.getElementById('vehicleInfo').innerHTML = '';
            if (chart) {
                chart.destroy();
            }
        }

        // Fonction pour afficher les informations du véhicule
        function displayVehicleInfo(mileageHistory) {
            const vehicleInfoDiv = document.getElementById('vehicleInfo');
            if (!mileageHistory || Object.keys(mileageHistory).length === 0) {
                vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
                return;
            }

            let html = '<h2>Historique des kilométrages :</h2>';
            html += '<table>';
            html += '<thead><tr><th>Date</th><th>Kilométrage</th></tr></thead><tbody>';

            const mileages = mileageHistory[0];
            const timestamps = mileageHistory[1];
            const creationTime = mileageHistory[2];
            const firstNameConcessionnaire = mileageHistory[3];
            const lastNameConcessionnaire = mileageHistory[4];

            if (!mileages || !timestamps || mileages.length === 0 || timestamps.length === 0) {
                vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
                return;
            }

            html += `<p><strong>Date de la première vente du véhicule :</strong> ${new Date(creationTime * 1000).toLocaleDateString('fr-FR')}</p>`;
            html += `<p><strong>Concessionnaire ayant enregistré le véhicule :</strong> ${firstNameConcessionnaire} ${lastNameConcessionnaire}</p>`;

            for (let i = 0; i < mileages.length; i++) {
                const date = new Date(timestamps[i] * 1000).toLocaleDateString('fr-FR');
                html += `<tr><td>${date}</td><td>${mileages[i]}</td></tr>`;
            }
            html += '</tbody></table>';

            vehicleInfoDiv.innerHTML = html;

            // Générer le graphique après avoir affiché les informations
            generateChart(timestamps, mileages);
        }

        // Fonction pour rechercher un véhicule
        async function searchVehicle(vin) {
            try {
                const accounts = await window.web3.eth.getAccounts();
                const account = accounts[0];

                // Appeler la fonction getMileageHistory du contrat pour récupérer l'historique des kilométrages
                const mileageHistory = await contract.methods.getMileageHistory(vin).call({ from: account });

                // Afficher les informations du véhicule
                displayVehicleInfo(mileageHistory);
            } catch (error) {
                console.error('Erreur lors de la recherche du véhicule:', error);
                document.getElementById('vehicleInfo').innerHTML = '<p>Erreur lors de la recherche du véhicule. Veuillez réessayer plus tard.</p>';
            }
        }

        // Fonction pour générer le graphique de kilométrage
        function generateChart(timestamps, mileages) {
            const ctx = document.getElementById('kilometrageChart').getContext('2d');
            const formattedTimestamps = timestamps.map(ts => new Date(ts * 1000));

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: formattedTimestamps,
                    datasets: [{
                        label: 'Kilométrage',
                        data: mileages,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1,
                        fill: true,
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            title: {
                                display: true,
                                text: 'Date et Heure'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Kilométrage'
                            }
                        }
                    }
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
