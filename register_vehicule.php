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
        <!-- Ajout de la case à cocher -->
        <label for="ajouterAleatoirement">Ajouter aléatoirement le kilométrage :</label>
        <input type="checkbox" id="ajouterAleatoirement" name="ajouterAleatoirement"><br><br>
        <input type="submit" value="Enregistrer">
    </form>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            let contract;
            const contractAddress = '0x6F02E69b327bA81921745255f2A762E03Aecf3c6';
            if (typeof window.ethereum !== 'undefined' || typeof window.web3 !== 'undefined') {
                window.web3 = new Web3(window.ethereum || window.web3.currentProvider);
                console.log('Web3 initialized with Ethereum provider');
                contract = new window.web3.eth.Contract(abi, contractAddress);
            } else {
                window.web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:7545'));
                console.log('Web3 initialized with HTTP provider');
                contract = new window.web3.eth.Contract(abi, contractAddress);
            }

            const vin = document.getElementById('vin').value;
            const mileage = document.getElementById('kilometrage').value;
            const currentDate = Math.floor(Date.now() / 1000); // Timestamp actuel en secondes
            const ajouterAleatoirement = document.getElementById('ajouterAleatoirement').checked;

            console.log('VIN:', vin);
            console.log('Mileage:', mileage);
            console.log('Current Date (timestamp):', currentDate);
            
            try {
                const accounts = await window.web3.eth.getAccounts();
                console.log('Accounts:', accounts);

                // Enregistrement de la voiture avec le VIN et le kilométrage fournis
                console.log('Registering car with VIN:', vin);
                await contract.methods.registerCar(vin, mileage, currentDate).send({ from: accounts[0], gas: 672280 });
                console.log('Car registered successfully');
                alert('Véhicule enregistré avec succès sur la blockchain.');

                if (ajouterAleatoirement) {
                    // Si la case à cocher est cochée, utilisez la fonction pour générer le kilométrage aléatoire
                    await genererEnregistrementsKilometrage(vin, mileage, currentDate, contract, accounts);
                    alert('Génération du kilométrage réalisée avec succès sur la blockchain.');
                    console.log('Kilométrage ajouté aléatoirement.');
                } else {
                    console.log('Kilométrage non ajouté aléatoirement.');
                }
            } catch (error) {
                console.error('Erreur lors de l\'enregistrement du véhicule :', error);
                alert('Erreur lors de l\'enregistrement du véhicule.');
            }
        });

        

        async function genererEnregistrementsKilometrage(vin, initialMileage, registrationTime, contract, accounts) {
    const semainesDansAnnee = 52; // On considère qu'on fait un relevé par semaine
    let mileage = initialMileage; // Utilisez le kilométrage initial fourni dans le formulaire
    let timestamp = registrationTime;
    console.log(mileage);
    for (let i = 0; i < semainesDansAnnee; i++) {
        // Générer un kilométrage aléatoire entre 0 et 1000
        let randomIncrement = Math.floor(Math.random() * 1001);
        // Convertir la chaîne de caractères mileage en entier, puis ajouter l'incrémentation aléatoire
        let newMileage = parseInt(mileage) + parseInt(randomIncrement);
        // Calculer le nouveau timestamp en ajoutant une semaine au timestamp précédent
        timestamp += (7 * 24 * 60 * 60);
        // Appeler la fonction updateMileage avec le nouveau kilométrage
        await contract.methods.updateMileage(vin, newMileage, parseInt(timestamp)).send({ from: accounts[0], gas: 672280 });

        // Réassigner newMileage à mileage pour la prochaine itération
        mileage = newMileage;
}

}




    </script>
</body>
</html>
