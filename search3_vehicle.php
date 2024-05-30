<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un véhicule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <nav>
        <button class="btn btn-outline-secondary btn-lg" type="button" onclick="window.location.href='index.php'"><i class="bi bi-house"></i></button>
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
            width: calc(100% - 100px);
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
        .hidden {
            display: none;
        }
        .button-container {
            display: flex;
            gap: 10px;
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
            <div class="button-container">
                <input type="text" id="vin" name="vin" required>
                <input type="submit" value="Rechercher">
            </div>
        </form>
        
        <!-- Onglets -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="mileage-tab" data-bs-toggle="tab" data-bs-target="#mileage" type="button" role="tab" aria-controls="mileage" aria-selected="true">Historique des kilométrages</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="repairs-tab" data-bs-toggle="tab" data-bs-target="#repairs" type="button" role="tab" aria-controls="repairs" aria-selected="false">Historique des réparations</button>
            </li>
        </ul>
        
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="mileage" role="tabpanel" aria-labelledby="mileage-tab">
            <canvas id="kilometrageChart" width="400" height="200"></canvas>
            <div class="vehicle-info" id="vehicleInfo">
                    <!-- Les informations du véhicule seront affichées ici -->
                </div>
                
            </div>
            <div class="tab-pane fade" id="repairs" role="tabpanel" aria-labelledby="repairs-tab">
                <div id="repairsInfo">
                    <h2>Historique des réparations :</h2>
                    <div id="repairsContent">
                        <!-- Les informations des réparations seront affichées ici -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        /* Initialisation de web3 : on utilise les injections "window.ethereum" et "window.web3" pour vérifier s'il y a bien
        une extension de navigateur qui fournit un réseau Ethereum comme Metamask, Ganache, ... */
        if (typeof window.ethereum !== 'undefined' || typeof window.web3 !== 'undefined') {
            window.web3 = new Web3(window.ethereum || window.web3.currentProvider); // Si la condition est vérifée, web3 s'initialisera avec currentProvider
        } else {
            window.web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:7545')); // Sinon, avec localhost:7545
        }

        // Adresse et ABI du smart contrat : étape essentielle pour intéragir avec le smart contract !
        const contractAddress = '0x81EFCe92D6FB25CcDaB6e3BaE3A090EE1676e138'; 
        const contract = new window.web3.eth.Contract(abi, contractAddress); // ces deux éléments sont disponibles sur Ganache
        let chart = null; // Initialisation de la variable "chart" qui contiendra le graphique des mileages"
        let currentVin = ''; // Déclaration d'une liste de caractères pour stocker le vin du véhicule

        // Gestion du formulaire de recherche
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault(); //empêche la page de se recharger lorsque l'utilisateur soumet le formulaire
            const vin = document.getElementById('vin').value.trim();
            if (vin.length === 0) { // On vérifie si l'utilisateur a bien saisi un "vin" et que la chaine de caractères n'est pas vide
                alert('Veuillez entrer un VIN valide.'); // Message qui s'affiche si jamais l'utilisateur ne saisit rien
                return;
            }
            clearPreviousResults();
            currentVin = vin; // On stocke le vin dans la variable (chaîne de caractères initialisée plus haut) pour plutard
            searchVehicle(vin); //Recherche du véhicule à partir du vin
        });

        // On efface les résultats précédents pour que l'utilisateur puisse chercher les infos d'un autre véhicule
        function clearPreviousResults() { //Création de la fonction
            document.getElementById('vehicleInfo').innerHTML = '';
            if (chart) {
                chart.destroy(); //on détruit le graphique pour l'adapter aux informations suivantes
            }
            document.getElementById('repairsContent').innerHTML = '';
        }

        // On affiche les informations du véhicule
        function displayVehicleInfo(mileageHistory) { //Création de la fonction "displayVehiculeInfo" qui prend en argument "mileageHistory" et affiche l'historique du kilométrage 
            const vehicleInfoDiv = document.getElementById('vehicleInfo'); //On stocke les infos dans la variable
            if (!mileageHistory || Object.keys(mileageHistory).length === 0) { // On vérifie si le tableau est vide 
                vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
                return;
            }

            let html = '<h2>Historique des kilométrages :</h2>';
            html += `<p><strong>Date de la première vente du véhicule :</strong> ${new Date(mileageHistory[2] * 1000).toLocaleDateString('fr-FR')}</p>`;
            html += `<p><strong>Concessionnaire ayant enregistré le véhicule :</strong> ${mileageHistory[3]} ${mileageHistory[4]}</p>`;
            html += '<table>';
            html += '<thead><tr><th>Date</th><th>Kilométrage</th></tr></thead><tbody>';

            const mileages = mileageHistory[0];
            const timestamps = mileageHistory[1];

            if (!mileages || !timestamps || mileages.length === 0 || timestamps.length === 0) {
                vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
                return;
            }

            for (let i = 0; i < mileages.length; i++) {
                const date = new Date(timestamps[i] * 1000).toLocaleDateString('fr-FR');
                html += `<tr><td>${date}</td><td>${mileages[i]}</td></tr>`;
            }
            html += '</tbody></table>';

            vehicleInfoDiv.innerHTML = html;

            // Générer le graphique après avoir affiché les informations
            generateChart(timestamps, mileages);
        }

        // Fonction pour afficher l'historique des réparations
        function displayRepairsInfo(repairHistory) {
            const repairsContentDiv = document.getElementById('repairsContent');
            if (!repairHistory || repairHistory[0].length === 0) {
                repairsContentDiv.innerHTML = '<p>Aucune réparation trouvée pour ce VIN.</p>';
                return;
            }

            let html = '<table>';
            html += '<thead><tr><th>Date</th><th>Titre</th><th>Description</th><th>Garagiste</th></tr></thead><tbody>';

            const timestamps = repairHistory[0];
            const titles = repairHistory[1];
            const descriptions = repairHistory[2];
            const firstNamesGaragiste = repairHistory[3];
            const lastNamesGaragiste = repairHistory[4];

            for (let i = 0; i < timestamps.length; i++) {
                const date = new Date(timestamps[i] * 1000).toLocaleDateString('fr-FR');
                html += `<tr><td>${date}</td><td>${titles[i]}</td><td>${descriptions[i]}</td><td>${firstNamesGaragiste[i]} ${lastNamesGaragiste[i]}</td></tr>`;
            }
            html += '</tbody></table>';

            repairsContentDiv.innerHTML = html;
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

                // Récupérer les informations de réparation et stocker
                const repairHistory = await contract.methods.getInterventionHistory(vin).call({ from: account });

                // Afficher les informations des réparations
                displayRepairsInfo(repairHistory);

            } catch (error) {
                console.error('Erreur lors de la recherche du véhicule:', error);
                document.getElementById('vehicleInfo').innerHTML = '<p>Erreur lors de la recherche du véhicule. Veuillez réessayer plus tard.</p>';
            }
        }

        // Fonction pour générer le graphique de kilométrage
        function generateChart(timestamps, mileages) {
            const ctx = document.getElementById('kilometrageChart').getContext('2d');
            const formattedTimestamps = timestamps.map(ts => new Date(ts * 1000));

            if (chart) {
                chart.destroy();
            }

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
