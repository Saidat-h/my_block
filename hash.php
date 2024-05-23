<?php
$mot_de_passe = 'g'; // Remplacez par le mot de passe que vous souhaitez hacher
$hash = hash('sha256', $mot_de_passe);
echo "Le mot de passe haché est : " . $hash;
?>
