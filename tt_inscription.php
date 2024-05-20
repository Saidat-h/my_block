<?php
session_start();
require 'param.inc.php'; // Fichier de configuration pour la connexion à la base de données

$nom = htmlentities($_POST['nom']);
$prenom = htmlentities($_POST['prenom']);
$email = htmlentities($_POST['email']);
$mdp = htmlentities($_POST['mdp']);

$con = new mysqli($host, $login, $passwd, $dbname);
if ($con->connect_error) {
    die('Erreur de connexion (' . $con->connect_errno . ') ' . $con->connect_error);
} else {
    // Vérifier si l'email existe déjà
    $checkUserQuery = "SELECT * FROM vendeur WHERE email = '$email'";
    $result = $con->query($checkUserQuery);

    if ($result->num_rows > 0) {
        // L'utilisateur existe déjà, afficher un message d'erreur
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">';
        echo '<div id="bienvenue-toast" class="toast position-fixed top-50 start-50 translate-middle" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Cet email est déjà utilisé.
                </div>
              </div>';
        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>';
        echo "<script>
                setTimeout(function () {
                    var bienvenueToast = new bootstrap.Toast(document.getElementById('bienvenue-toast'));
                    bienvenueToast.show();
                }, 200);

                setTimeout(function () {
                    window.location.href = 'inscription.php';
                }, 4000);
              </script>";
    } else {
        // Hacher le mot de passe avec SHA-256
        $mdp_hache = hash('sha256', $mdp);

        // Insérer les données dans la base de données
        $insertUserQuery = "INSERT INTO vendeur (nom, prenom, email, mdp) VALUES ('$nom', '$prenom', '$email', '$mdp_hache')";

        if ($con->query($insertUserQuery) === TRUE) {
            echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">';
            echo '<div id="bienvenue-toast" class="toast position-fixed top-50 start-50 translate-middle" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Notification</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Inscription réussie !
                    </div>
                  </div>';
            echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>';
            echo "<script>
                    setTimeout(function () {
                        var bienvenueToast = new bootstrap.Toast(document.getElementById('bienvenue-toast'));
                        bienvenueToast.show();
                    }, 200);

                    setTimeout(function () {
                        window.location.href = 'inscription.php';
                    }, 4000);
                  </script>";
        } else {
            echo "Erreur lors de l'inscription : " . $con->error;
        }
    }
}

$con->close();
?>
