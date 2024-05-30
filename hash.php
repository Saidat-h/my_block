<?php
// Page pour lire le mot de passe pour nous admin pour saisir dans la bdd directement 
$mot_de_passe = 'g'; // Mot de passe à hacher : "g"
$hash = hash('sha256', $mot_de_passe);
echo "Le mot de passe haché est : " . $hash;
?>
