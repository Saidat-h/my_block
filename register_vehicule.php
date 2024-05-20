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
</head>
<body>
    <h1>Enregistrer un véhicule</h1>
    <form action="tt_register.php" method="post">
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
</body>
</html>
