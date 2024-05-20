<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
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
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue sur notre site de gestion de véhicules</h1>
    </header>
    <nav>
        <ul>
            <li><a href="register_vehicule.php">Enregistrer un véhicule</a></li>
            <li><a href="search_vehicule.php">Rechercher un véhicule</a></li>
            <li><a href="inscription.php">Inscription</a></li>
            <li><a href="connexion.php">Connexion</a></li>
            <li><a href="tt_deconnexion.php">Deconnexion</a></li>
        </ul>
    </nav>
    <main>
        <!-- Section de l'image de la location -->
        <div class="location">
            <img src="location.jpeg" alt="Image de notre emplacement">
        </div>
        <!-- Contenu principal ici -->
    </main>
    <footer>
        <p>&copy; 2024 Tous droits réservés</p>
    </footer>
</body>
</html>
