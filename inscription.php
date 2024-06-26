
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Inscription</title>
    <style>
        .form-label {
            color: white;
        }
        body {
            background-color: #343a40; /* Optionnel: couleur de fond pour une meilleure visibilité */
        }
    </style>
</head>
<body>
    <section class="home">
        <div class="container">
            <h1 style="color: white;">Inscription</h1>
            <?php
            session_start();
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-info" id="message">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
            }
            ?>
            <form method="POST" action="tt_inscription.php">
                <div class="container">
                    <div class="row my-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom..." required>
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom..." required>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Votre email..." required>
                        </div>
                        <div class="col-md-6">
                            <label for="mdp" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Votre mot de passe..." required>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="d-grid gap-2 d-md-block">
                            <button class="btn btn-outline-primary" type="submit">Terminer l'Inscription</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript pour masquer le message après 1 seconde
        window.addEventListener('DOMContentLoaded', (event) => {
            const messageElement = document.getElementById('message');
            if (messageElement) {
                setTimeout(() => {
                    messageElement.style.display = 'none';
                }, 1000); // 1000 millisecondes = 1 seconde
            }
        });
    </script>
</body>
</html>
