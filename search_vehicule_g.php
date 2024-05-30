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
    <title>Rechercher un véhicule (Historique)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
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
    <nav>
        <button class="btn btn-outline-secondary btn-lg" type="button" onclick="window.location.href='index_garagiste.php'"><i class="bi bi-house"></i></button>
    <form action="tt_deconnexion.php" method="post" style="display:inline;">
        <button class="btn btn-outline-secondary btn-lg" type="submit"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
    </form>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.1/dist/web3.min.js"></script>
    <script src="abi.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>
<body>
    <div class="container">
        <h1>Historique des réparations</h1>
        <form id="searchForm">
            <label for="vin">Entrez le Numéro du Véhicule :</label>
            <input type="text" id="vin" name="vin" required><br><br>
            <input type="submit" value="Rechercher">
        </form>
        <div class="row">
            <div class="col-md-12">
                <div class="vehicle-info" id="vehicleInfo">
                    <!-- Les informations du véhicule seront affichées ici -->
                </div>
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
        const contractAddress = '0x81EFCe92D6FB25CcDaB6e3BaE3A090EE1676e138';
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

        // Fonction pour rechercher un véhicule
        async function searchVehicle(vin) {
            try {
                const accounts = await window.web3.eth.getAccounts();
                const account = accounts[0];

                // Appeler la fonction getInterventionHistory du contrat pour récupérer l'historique des interventions
                const InterventionRecord = await contract.methods.getInterventionHistory(vin).call({ from: account });

                // Afficher les informations du véhicule
                displayVehicleInfo(InterventionRecord);
            } catch (error) {
                console.error('Erreur lors de la recherche du véhicule:', error);
                document.getElementById('vehicleInfo').innerHTML = '<p>Erreur lors de la recherche du véhicule. Veuillez réessayer plus tard.</p>';
            }
        }

        // Fonction pour effacer les résultats précédents, sinonle graphique précédent empêchait l'affichage du nouveau tableau
        function clearPreviousResults() {
            document.getElementById('vehicleInfo').innerHTML = '';
            if (chart) {
                chart.destroy();
            }
        }

        // Fonction pour afficher les informations du véhicule
        function displayVehicleInfo(InterventionRecord) {
            const vehicleInfoDiv = document.getElementById('vehicleInfo');

            //Vérification si kilométrage existant pour vin
            if (!InterventionRecord || InterventionRecord[0].length === 0) {
                vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
                return;
            }

            //Affichage du tableau de l'historique
            let html = '<h2>Historique des interventions :</h2>';
            html += '<table>';
            html += '<thead><tr><th>Date</th><th>Garagiste</th><th>Titre de l\'intervention</th><th>Description</th></tr></thead><tbody>';

            //Déclaration +Initialisation
            const timestamps = InterventionRecord[0];
            const titles = InterventionRecord[1];
            const descriptions = InterventionRecord[2];
            const firstNameGaragiste = InterventionRecord[3];
            const lastNameGaragiste = InterventionRecord[4];

            //Remplissage du tableau
            for (let i = 0; i < titles.length; i++) {
                const date = new Date(timestamps[i] * 1000).toLocaleDateString('fr-FR');
                html += `<tr><td>${date}</td><td>${firstNameGaragiste[i]} ${lastNameGaragiste[i]}</td><td>${titles[i]}</td><td>${descriptions[i]}</td></tr>`;
            }
            html += '</tbody></table>';

            vehicleInfoDiv.innerHTML = html;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
