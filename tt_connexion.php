<?php
session_start(); // Démarrer la session pour les messages

$email = htmlentities($_POST['email']);
$mdp = htmlentities($_POST['mdp']);

// Hashage du mot de passe avec l'algorithme SHA-256
$mdp_hash = hash('sha256', $mdp);

// Connexion à la base de données
require_once("param.inc.php");
$mysqli = new mysqli($host, $login, $passwd, $dbname);

if ($mysqli->connect_error) {
    die('Erreur de connexion (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Préparation de la requête SQL
if ($stmt = $mysqli->prepare("SELECT * FROM vendeur WHERE email=? LIMIT 1")) {
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Vérifier s'il y a des erreurs dans la requête
    if ($stmt->error) {
        die('Erreur de requête : ' . $stmt->error);
    }

    $result = $stmt->get_result();

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Vérifier le mot de passe avec le mot de passe hashé stocké dans la base de données
        if ($mdp_hash === $row["mdp"]) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['PROFILE'] = $row;
            $_SESSION['message'] = "Connexion réussie.";
            echo '<script>window.onload = function() {
                    setTimeout(function() {
                        window.location.href = "register_vehicule.php";
                    }, 100); // Rediriger après 4 secondes
                }</script>';
        } else {
            // Redirection si le mot de passe est incorrect
            $_SESSION['message'] = "Mot de passe incorrect.";
            echo '<script>window.onload = function() {
                    alert("' . $_SESSION['message'] . '");
                    setTimeout(function() {
                        window.location.href = "connexion.php";
                    }, 100); // Rediriger après 4 secondes
                }</script>';
        }
    } else {
        // Redirection si l'utilisateur n'existe pas
        $_SESSION['message'] = "Identifiant inexistant.";
        echo '<script>window.onload = function() {
                alert("' . $_SESSION['message'] . '");
                setTimeout(function() {
                    window.location.href = "connexion.php";
                }, 100); // Rediriger après 4 secondes
            }</script>';
    }

    // Fermer le statement
    $stmt->close();
} else {
    // Gérer l'erreur si la préparation de la requête échoue
    die('Erreur de préparation de la requête : ' . $mysqli->error);
}

// Fermer la connexion à la base de données
$mysqli->close();
?>
