
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
            window.ethereum.request({ method: 'eth_requestAccounts' });
        } else {
            window.web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:8545'));
        }
 
        // Adresse et ABI du contrat CarRegistry
        const contractAddress = '0x878F4af3BF8bB4736715aA9d8131c855Dbbd15E1';
        const contractABI = [
            {
                "constant": false,
                "inputs": [
                    {
                        "name": "vin",
                        "type": "string"
                    }
                ],
                "name": "registerCar",
                "outputs": [],
                "payable": false,
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "constant": false,
                "inputs": [
                    {
                        "name": "vin",
                        "type": "string"
                    },
                    {
                        "name": "newMileage",
                        "type": "uint256"
                    },
                    {
                        "name": "timestamp",
                        "type": "uint256"
                    }
                ],
                "name": "updateMileage",
                "outputs": [],
                "payable": false,
                "stateMutability": "nonpayable",
                "type": "function"
            },
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
        ];[10:13] DJULU PENGHE Bonté
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
