
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Concessionnaire</title>
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
            <li><a href="register_vehicule.php">Enregistrer un véhicule </a></li>
            <li><a href="search_vehicule_c.php">Rechercher un véhicule</a></li>
            <form action="tt_deconnexion.php" method="post" style="display:inline;">
            <button class="btn btn-outline-secondary btn-lg" type="submit"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
        </form>
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
 
</body>
</html>