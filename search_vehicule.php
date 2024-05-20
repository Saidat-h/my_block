
<?php
session_start(); // Démarrer la session
 
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['PROFILE']) || $_SESSION['PROFILE'] == null) {
    // Rediriger vers la page de connexion
    header("Location: index.php");
    exit; // Arrêter l'exécution du script après la redirection
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrer un véhicule</title>
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
        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        textarea {
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
    </style>
    <!-- Inclure la bibliothèque web3.js depuis un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.1/dist/web3.min.js"></script>
</head>
<body>
    <h1>Enregistrer un véhicule</h1>
    <form id="registerForm">
        <label for="date">Date et Heure :</label>
        <input type="datetime-local" id="date" name="date" required><br><br>
        <label for="vin">VIN :</label>
        <input type="text" id="vin" name="vin" required><br><br>
        <label for="kilometrage">Kilométrage :</label>
        <input type="number" id="kilometrage" name="kilometrage" required><br><br>
        <label for="info_complementaires">Informations complémentaires :</label>
        <textarea id="info_complementaires" name="info_complementaires"></textarea><br><br>
        <input type="submit" value="Enregistrer">
    </form>
 
    <script>
        document.getElementById('registerForm').onsubmit = async function(event) {
            event.preventDefault();
            
            const vin = document.getElementById('vin').value;
            const date = document.getElementById('date').value;
            const mileage = document.getElementById('kilometrage').value;
 
            if (typeof window.ethereum !== 'undefined' || typeof window.web3 !== 'undefined') {
                window.web3 = new Web3(window.ethereum || window.web3.currentProvider);
                await window.ethereum.request({ method: 'eth_requestAccounts' });
            } else {
                alert('Vous devez installer MetaMask !');
                return;
            }
 
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
            ];
 
            const contractAddress = 'VOTRE_ADRESSE_CONTRAT';
            const contract = new web3.eth.Contract(contractABI, contractAddress);
            const accounts = await web3.eth.getAccounts();
 
            try {
                await contract.methods.registerCar(vin).send({ from: accounts[0] });
                await contract.methods.updateMileage(vin, mileage, Math.floor(new Date(date).getTime() / 1000)).send({ from: accounts[0] });
                alert('Véhicule enregistré avec succès sur la blockchain.');
            } catch (error) {
                console.error('Erreur lors de l\'enregistrement du véhicule :', error);
                alert('Erreur lors de l\'enregistrement du véhicule.');
            }
        };
    </script>
</body>
</html>