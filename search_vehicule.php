<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un véhicule</title>
    <nav>
        <ul>
            <li><a href="index.php">Retour à l'accueil</a></li>
        </ul>
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
    </style>
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.1/dist/web3.min.js"></script>
    <script src="abi.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>
<body>
    <h1>Rechercher un véhicule</h1>
    <form id="searchForm">
        <label for="vin">Entrez le VIN :</label>
        <input type="text" id="vin" name="vin" required><br><br>
        <input type="submit" value="Rechercher">
    </form>
    <div class="vehicle-info" id="vehicleInfo">
        <!-- Les informations du véhicule seront affichées ici -->
    </div>
    <canvas id="kilometrageChart" width="400" height="200"></canvas>

    <script>
        // Initialiser web3
        if (typeof window.ethereum !== 'undefined' || typeof window.web3 !== 'undefined') {
            window.web3 = new Web3(window.ethereum || window.web3.currentProvider);
        } else {
            window.web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:7545'));
        }

        // Adresse du contrat CarRegistry
        const contractAddress = '0x5915db7f6186D64AA929BD3eB3F474AB727B0966';
        const contract = new window.web3.eth.Contract(abi, contractAddress);

        // Gérer le formulaire de recherche
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const vin = document.getElementById('vin').value.trim();
            if (vin.length === 0) {
                alert('Veuillez entrer un VIN valide.');
                return;
            }
            searchVehicle(vin);
        });

        // Fonction pour afficher les informations du véhicule
        function displayVehicleInfo(mileageHistory) {
            const vehicleInfoDiv = document.getElementById('vehicleInfo');
            if (!mileageHistory || Object.keys(mileageHistory).length === 0) {
                vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
                return;
            }

            let html = '<h2>Historique des kilométrages :</h2>';
            html += '<ul>';

            const mileages = mileageHistory[0];
            const timestamps = mileageHistory[1];

            if (!mileages || !timestamps || mileages.length === 0 || timestamps.length === 0) {
                vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
                return;
            }

            for (let i = 0; i < mileages.length; i++) {
                html += `<li><strong>Kilométrage :</strong> ${mileages[i]}, <strong>Timestamp :</strong> ${new Date(timestamps[i] * 1000).toLocaleString()}</li>`;
            }
            html += '</ul>';

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

            const chart = new Chart(ctx, {
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
</body>
</html>
