<?php
session_start(); // Démarrer la session

// Détruire toutes les données de session
session_destroy();

// Rediriger vers la page de connexion ou une autre page après la déconnexion
header("Location: index.php");
exit;
?>
