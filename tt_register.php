<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de la recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <!-- Ajoutez d'autres liens CSS, scripts, etc. ici -->
</head>
<body>
<?php
session_start();
require_once("param.inc.php");
$date=htmlentities($_POST['date']);
$vin =htmlentities( $_POST['vin']);
$kilometrage = htmlentities($_POST['kilometrage']);
$info_complementaires = htmlentities($_POST['info_complementaires']);
$utilisateur=$_SESSION['PROFILE'];
$idV=$utilisateur['id'];
$con = new mysqli($host, $login, $passwd, $dbname);
if ($con->connect_error) {
    die('Erreur de connexion (' . $con->connect_errno . ') '
            . $con->connect_error);
} else {
    // Vérifier si le vehicule existe déjà
    $checkUserQuery = "SELECT * FROM vehicule WHERE vin = '$vin'";
    $result = $con->query($checkUserQuery);

    if ($result->num_rows > 0) {
        // L'utilisateur existe déjà, afficher un message d'erreur
        echo ' <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
 
 
    <script src="../js/bootstrap.min.js"></script>';
 
      echo '<div id="bienvenue-toast" class="toast position-fixed top-50 start-50 translate-middle" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="me-auto">Notification</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
      Véhicule déjà enrégistré.!
      </div>
    </div>';
 
      echo ' <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>';
 
 
      echo "<script>
      // Affiche le toast de bienvenue après 1 seconde
 
      setTimeout(function () {
        var bienvenueToast = new bootstrap.Toast(document.getElementById('bienvenue-toast'));
        bienvenueToast.show();
        toast.hide();
 
      }, 200);
   
      setTimeout(function () {
          window.location.href = 'index.php';
        }, 4000);
    </script>";
    }  else {
      

      // Insérer l'utilisateur avec le mot de passe haché
      $insertUserQuery = "INSERT INTO vehicule (date_heure, vin, kilometrage, info_complementaires,idV) values ('$date', '$vin', '$kilometrage', '$info_complementaires','$idV')";

      if ($con->query($insertUserQuery) === TRUE) {
        echo ' <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
   
   
      <script src="../js/bootstrap.min.js"></script>';
   
        echo '<div id="bienvenue-toast" class="toast position-fixed top-50 start-50 translate-middle" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="me-auto">Notification</strong>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          Enrégistrement réuissi
        </div>
      </div>';
   
        echo ' <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>';
   
   
        echo "<script>
        // Affiche le toast de bienvenue après 1 seconde
   
        setTimeout(function () {
          var bienvenueToast = new bootstrap.Toast(document.getElementById('bienvenue-toast'));
          bienvenueToast.show();
          toast.hide();
   
        }, 200);
     
        setTimeout(function () {
            window.location.href = 'index.php';
          }, 4000);
      </script>";
      } else {
          echo "Erreur lors de l'inscription : " . $con->error;
      }
  }
}

$con->close();


?>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>