<?php
session_start();

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
if ($stmt = $mysqli->prepare("SELECT * FROM utilisateur WHERE email=? LIMIT 1")) {
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

            // Rediriger en fonction du type d'utilisateur
            if ($row['type'] === 'c') {
                echo '<script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.location.href = "index_concessionnaire.php";
                            }, 100); // Rediriger après 0,1 seconde
                        }
                      </script>';
            } else if ($row['type'] === 'g') {
                echo '<script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.location.href = "index_garagiste.php";
                            }, 100); // Rediriger après 0,1 seconde
                        }
                      </script>';
            } else {
                echo '<script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.location.href = "index.php";
                            }, 100); // Rediriger après 0,1 seconde
                        }
                      </script>';
            }
        } else {
            // Redirection si le mot de passe est incorrect
            $_SESSION['message'] = "Mot de passe incorrect.";
            echo '<script>
                    window.onload = function() {
                        alert("' . $_SESSION['message'] . '");
                        setTimeout(function() {
                            window.location.href = "connexion.php";
                        }, 100); // Rediriger après 0,1 seconde
                    }
                  </script>';
        }
    } else {
        // Redirection si l'utilisateur n'existe pas
        $_SESSION['message'] = "Identifiant inexistant.";
        echo '<script>
                window.onload = function() {
                    alert("' . $_SESSION['message'] . '");
                    setTimeout(function() {
                        window.location.href = "connexion.php";
                    }, 100); // Rediriger après 0,1 seconde
                }
              </script>';
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
