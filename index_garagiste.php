
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Garagiste</title>
    <style>
        /* Appliquer un minimum de hauteur à la page pour forcer le footer à être en bas */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
 
        header {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
        }
 
        nav {
            background-color: #333;
        }
 
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            text-align: center;
        }
 
        nav li {
            display: inline;
        }
 
        nav a {
            display: inline-block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
 
        nav a:hover {
            background-color: #111;
        }
 
        main {
            flex: 1;
            padding: 20px;
        }
 
        footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
 
        .location {
            text-align: center;
            margin: 20px 0;
        }
 
        .location img {
            max-width: 100%;
            height: auto;
        }
 
        /* Styles pour le formulaire de recherche */
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
    <!-- Inclure la bibliothèque web3.js depuis un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.1/dist/web3.min.js"></script>
</head>
<body>
    <header>
        <h1>Bienvenue sur notre site de gestion de véhicules</h1>
    </header>
    <nav>
        <ul>
            <li><a href="register_garagiste.php">Mettre à jour Historique d'un véhicule </a></li>
            <li><a href="search_vehicule_g.php">Rechercher un véhicule</a></li>
            
        </ul>
    </nav>
    <main>
        <!-- Section de l'image de la location -->
        <div class="location">
            <img src="location.jpeg" alt="Image de notre emplacement">
        </div>
      
    </main>
    <footer>
        <p>&copy; 2024 Tous droits réservés</p>
    </footer>
 
    <!-- Script pour configurer et initialiser web3.js -->
    <script>
        // Vérifier si Web3 a été injecté par le navigateur (Mist/MetaMask)
        if (typeof window.ethereum !== 'undefined' || typeof window.web3 !== 'undefined') {
            // Utiliser Mist/MetaMask's provider
            window.web3 = new Web3(window.ethereum || window.web3.currentProvider);
            // Demander la permission d'accéder aux comptes
            window.ethereum.request({ method: 'eth_requestAccounts' });
        } else {
            // Sinon, connecter à Ganache sur http://localhost:8545
            window.web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:8545'));
        }
 
        // Gérer le formulaire de recherche de véhicule
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const vin = document.getElementById('vin').value.trim();
            if (vin.length === 0) {
                alert('Veuillez entrer un VIN valide.');
                return;
            }
            searchVehicle(vin);
        });
 
        async function searchVehicle(vin) {
            try {
                const accounts = await window.web3.eth.getAccounts();
                const account = accounts[0];
 
                // Adresse du contrat CarRegistry (remplacez par votre adresse de contrat réelle)
                const contractAddress = 'VOTRE_ADRESSE_CONTRAT';
                const contractABI = [
                    {
                        "constant": true,
                        "inputs": [
                            {
                                "name": "vin",
                                "type": "string"
                            }
                        ],
                        "name": "getMileageHistory",
                        "outputs": [
                            {
                                "components": [
                                    {
                                        "name": "mileage",
                                        "type": "uint256"
                                    },
                                    {
                                        "name": "timestamp",
                                        "type": "uint256"
                                    }
                                ],
                                "name": "",
                                "type": "tuple[]"
                            }
                        ],
                        "payable": false,
                        "stateMutability": "view",
                        "type": "function"
                    }
                ];
 
                const contract = new window.web3.eth.Contract(contractABI, contractAddress);
 
                // Appeler la fonction getMileageHistory du contrat pour récupérer l'historique des kilométrages
                const mileageHistory = await contract.methods.getMileageHistory(vin).call({ from: account });
 
                // Afficher les informations du véhicule
                displayVehicleInfo(mileageHistory);
            } catch (error) {
                console.error('Erreur lors de la recherche du véhicule:', error);
            }
        }
 
        function displayVehicleInfo(mileageHistory) {
            const vehicleInfoDiv = document.getElementById('vehicleInfo');
            if (mileageHistory.length === 0) {
                vehicleInfoDiv.innerHTML = '<p>Aucun historique trouvé pour ce VIN.</p>';
                return;
            }
 
            let html = '<h2>Historique des kilométrages :</h2>';
            html += '<ul>';
            mileageHistory.forEach(record => {
                html += `<li><strong>Kilométrage :</strong> ${record.mileage}, <strong>Timestamp :</strong> ${new Date(record.timestamp * 1000).toLocaleString()}</li>`;
            });
            html += '</ul>';
 
            vehicleInfoDiv.innerHTML = html;
        }
    </script>
</body>
</html>