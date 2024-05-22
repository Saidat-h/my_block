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
        input[type="number"] {
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
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.1/dist/web3.min.js"></script>
    <script src="abi.js"></script>
</head>
<body>
    <h1>Enregistrer un véhicule</h1>
    <form id="registerForm">
        <label for="vin">VIN :</label>
        <input type="text" id="vin" name="vin" required><br><br>
        <label for="kilometrage">Kilométrage :</label>
        <input type="number" id="kilometrage" name="kilometrage" required><br><br>
        <input type="submit" value="Enregistrer">
    </form>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const vin = document.getElementById('vin').value;
            const mileage = document.getElementById('kilometrage').value;
            const currentDate = Math.floor(Date.now() / 1000); // Timestamp actuel en secondes

            console.log('VIN:', vin);
            console.log('Mileage:', mileage);
            console.log('Current Date (timestamp):', currentDate);

            if (typeof window.ethereum !== 'undefined' || typeof window.web3 !== 'undefined') {
                window.web3 = new Web3(window.ethereum || window.web3.currentProvider);
                console.log('Web3 initialized with Ethereum provider');
            } else {
                window.web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:7545'));
                console.log('Web3 initialized with HTTP provider');
            }

            const contractAddress = '0x6F02E69b327bA81921745255f2A762E03Aecf3c6';
            const contract = new window.web3.eth.Contract(abi, contractAddress);

            try {
                const accounts = await web3.eth.getAccounts();
                console.log('Accounts:', accounts);

                // Après avoir obtenu les comptes et le contrat...

                console.log('Registering car with VIN:', vin);
                await contract.methods.registerCar(vin, mileage, currentDate).send({ from: accounts[0], gas: 672280 });
                console.log('Car registered successfully');


                

                alert('Véhicule enregistré avec succès sur la blockchain.');
            } catch (error) {
                console.error('Erreur lors de l\'enregistrement du véhicule :', error);
                alert('Erreur lors de l\'enregistrement du véhicule.');
            }
        });
    </script>
</body>
</html>
