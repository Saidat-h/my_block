<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un concessionnaire
if (!isset($_SESSION['PROFILE']) || $_SESSION['PROFILE']['type'] !== 'c') {
    $_SESSION['message'] = "Vous devez être connecté en tant que concessionnaire pour accéder à cette page.";
    header('Location: connexion.php');
    exit();
}

$firstNameConcessionnaire = $_SESSION['PROFILE']['prenom'];
$lastNameConcessionnaire = $_SESSION['PROFILE']['nom'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Enregistrer un véhicule</title>
    <nav>
    <button class="btn btn-outline-secondary btn-lg" type="button" onclick="window.location.href='index_concessionnaire.php'"><i class="bi bi-house"></i></button>
    <form action="tt_deconnexion.php" method="post" style="display:inline;">
        <button class="btn btn-outline-secondary btn-lg" type="submit"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
    </form>
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
        input[type="text"],
        input[type="number"],
        input[type="date"] {
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
        <label for="vin">Numéro d'identification du véhicule:</label>
        <input type="text" id="vin" name="vin" required><br><br>

        <label for="creationDate">Date de la première vente du véhicule:</label>
        <input type="date" id="creationDate" name="creationDate" required><br><br>

        <label for="kilometrage">Kilométrage du véhicule lors de l'enregistrement:</label>
        <input type="number" id="kilometrage" name="kilometrage" required><br><br>

        <label for="ajouterAleatoirement">SIMULATION : Ajouter aléatoirement le kilométrage:</label>
        <input type="checkbox" id="ajouterAleatoirement" name="ajouterAleatoirement"><br><br>

        <input type="hidden" id="firstNameConcessionnaire" value="<?php echo $firstNameConcessionnaire; ?>">
        <input type="hidden" id="lastNameConcessionnaire" value="<?php echo $lastNameConcessionnaire; ?>">

        <input type="submit" value="Enregistrer">
    </form>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            let contract;
            const contractAddress = '0x81EFCe92D6FB25CcDaB6e3BaE3A090EE1676e138'; // Assurez-vous que cette adresse est correcte
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
            const creationDate = Math.floor(new Date(document.getElementById('creationDate').value).getTime() / 1000); // Convertir en timestamp Unix
            const currentDate = Math.floor(Date.now() / 1000); // Timestamp actuel en secondes
            const firstNameConcessionnaire = document.getElementById('firstNameConcessionnaire').value;
            const lastNameConcessionnaire = document.getElementById('lastNameConcessionnaire').value;
            const ajouterAleatoirement = document.getElementById('ajouterAleatoirement').checked;

            console.log('VIN:', vin);
            console.log('Mileage:', mileage);
            console.log('Creation Date (timestamp):', creationDate);
            console.log('Current Date (timestamp):', currentDate);
            console.log('First Name Concessionnaire:', firstNameConcessionnaire);
            console.log('Last Name Concessionnaire:', lastNameConcessionnaire);

            try {
                const accounts = await window.web3.eth.getAccounts();
                console.log('Accounts:', accounts);

                // Enregistrement de la voiture avec le VIN, le kilométrage, la date actuelle, la date de création et les informations du concessionnaire
                console.log('Registering car with VIN:', vin);
                await contract.methods.registerCar(vin, mileage, currentDate, creationDate, firstNameConcessionnaire, lastNameConcessionnaire).send({ from: accounts[0], gas: 672280 });
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
                alert('Erreur lors de l\'enregistrement du véhicule. Le véhicule est déjà enregistré ou la connexion à la blockchain n\'a pas aboutie');
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
                await contract.methods.updateMileage(vin, newMileage, timestamp).send({ from: accounts[0], gas: 672280 });

                // Réassigner newMileage à mileage pour la prochaine itération
                mileage = newMileage;
            }
        }
    </script>
</body>
</html>
