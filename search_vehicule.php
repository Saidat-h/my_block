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
    </style>
</head>
<body>
    <h1>Rechercher un véhicule</h1>
    <form action="tt_search.php" method="post">
        <label for="vin">Entrez le VIN :</label>
        <input type="text" id="vin" name="vin" required><br><br>
        <input type="submit" value="Rechercher">
    </form>

    