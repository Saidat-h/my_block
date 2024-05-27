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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Ajouter une réparation faite</title>
    <nav>
    <button class="btn btn-outline-secondary btn-lg" type="button" onclick="window.location.href='index_garagiste.php'"><i class="bi bi-house"></i></button>
    <form action="tt_deconnexion.php" method="post" style="display:inline;">
            <button class="btn btn-outline-secondary btn-lg" type="submit"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
        </form>
    <nav>
    
</nav>

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

    <h1>Ajouter une réparation faite</h1>
    <form id="registerForm">
        <label for="vin">Numéro d'identification du véhicule:</label>
        <input type="text" id="vin" name="vin" required><br><br>

        <label for="dateIntervention">Date de l'intervention:</label>
        <input type="date" id="dateIntervention" name="dateIntervention" required><br><br>

        <label for="title">Titre de l'intervention</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Description de l'intervention</label>
        <input type="text" id="description" name="description"><br><br>

        <input type="hidden" id="firstNameGaragiste" value="<?php echo $firstNameGaragiste; ?>">
        <input type="hidden" id="lastNameGaragiste" value="<?php echo $lastNameGaragiste; ?>">

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
            const title = document.getElementById('title').value;
            const dateIntervention = Math.floor(new Date(document.getElementById('dateIntervention').value).getTime() / 1000); // Convertir en timestamp Unix
            const description = document.getElementById('description').value;
            const firstNameGaragiste = document.getElementById('firstNameGaragiste').value;
            const lastNameGaragiste = document.getElementById('lastNameGaragiste').value;

            console.log('VIN:', vin);
            console.log('Titre:', title);
            console.log('Date (timestamp):', dateIntervention);
            console.log('Description:', description);
            console.log('First Name G:', firstNameGaragiste);
            console.log('Last Name G:', lastNameGaragiste);

            try {
                const accounts = await window.web3.eth.getAccounts();
                console.log('Accounts:', accounts);

                // Enregistrement de l'intervention pour le véhicule avec le VIN
                console.log('Registering intervention for car with VIN:', vin);
                await contract.methods.addIntervention(vin, dateIntervention, title, description, firstNameGaragiste, lastNameGaragiste).send({ from: accounts[0], gas: 672280 });
                console.log('Intervention registered successfully');
                alert('Intervention mise à jour avec succès sur la blockchain.');

            } catch (error) {
                console.error('Erreur lors de la mise à jour de l\'intervention du véhicule :', error);
                alert('Erreur lors de l\'enregistrement de l\'intervention. La connexion à la blockchain n\'a pas abouti ou une autre erreur est survenue.');
            }
        });
    </script>
</body>
</html>
