<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un véhicule</title>
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
 
    <script>
        // Initialiser web3
        if (typeof window.ethereum !== 'undefined' || typeof window.web3 !== 'undefined') {
            window.web3 = new Web3(window.ethereum || window.web3.currentProvider);
        } else {
            window.web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:7545'));
        }
 
        // Adresse du contrat CarRegistry
        const contractAddress = '0x6F02E69b327bA81921745255f2A762E03Aecf3c6';
 
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
    
    // Vérifier si mileageHistory est défini et est un tableau
    if (!mileageHistory || Object.keys(mileageHistory).length === 0) {
        vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
        return;
    }

    let html = '<h2>Historique des kilométrages :</h2>';
    html += '<ul>';

    // Accéder directement aux tableaux dans l'objet mileageHistory
    const mileages = mileageHistory[0];
    const timestamps = mileageHistory[1];

    if (!mileages || !timestamps || mileages.length === 0 || timestamps.length === 0) {
        vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
        return;
    }

    // Boucler à travers les tableaux de mileages et de timestamps
    for (let i = 0; i < mileages.length; i++) {
        html += `<li><strong>Kilométrage :</strong> ${mileages[i]}, <strong>Timestamp :</strong> ${new Date(timestamps[i] * 1000).toLocaleString()}</li>`;
    }
    html += '</ul>';

    vehicleInfoDiv.innerHTML = html;
}


        // Fonction pour rechercher un véhicule
        async function searchVehicle(vin) {
            try {
                console.log('Fetching accounts...');
                const accounts = await window.web3.eth.getAccounts();
                console.log('Accounts:', accounts);
                const account = accounts[0];
 
                console.log('Calling getMileageHistory for VIN:', vin);
                // Appeler la fonction getMileageHistory du contrat pour récupérer l'historique des kilométrages
                const mileageHistory = await contract.methods.getMileageHistory(vin).call({ from: account });
 
                console.log('Mileage history:', mileageHistory);
                // Afficher les informations du véhicule
                displayVehicleInfo(mileageHistory);
            } catch (error) {
                console.error('Erreur lors de la recherche du véhicule:', error);
                document.getElementById('vehicleInfo').innerHTML = '<p>Erreur lors de la recherche du véhicule. Veuillez réessayer plus tard.</p>';
            }
        }
    </script>
</body>
</html>
