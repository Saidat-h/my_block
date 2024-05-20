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

    $vin = htmlentities($_POST['vin']);

    $con = new mysqli($host, $login, $passwd, $dbname);
    

    $result = $con->query("SELECT date_heure,vin,kilometrage,info_complementaires, nom,prenom FROM vehicule INNER JOIN vendeur ON vehicule.idV = vendeur.id WHERE vin = '$vin'");
    if ($result) {
        // Afficher les jeux s'il y en a
        if ($result->num_rows > 0) {
            echo '<h2>Véhicule Recherché</h2>';
            echo '<table class="table mx-auto" style="color: black;">';
            echo '<tr>
                    <th>Date et Heure</th>
                    <th>Vin</th> 
                    <th>Kilométrage</th>
                    <th>Informations Complémentaires</th>
                    <th>nom</th>
                    <th>prenom</th>
                  </tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['date_heure'] . '</td>';
                echo '<td>' . $row['vin'] . '</td>';
                echo '<td>' . $row['kilometrage'] . '</td>';
                echo '<td>' . $row['info_complementaires'] . '</td>';
                echo '<td>' . $row['nom'] . '</td>';
                echo '<td>' . $row['prenom'] . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            // Aucun véhicule trouvé avec ce VIN
            echo ' <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
 
 
    <script src="../js/bootstrap.min.js"></script>';
 
      echo '<div id="bienvenue-toast" class="toast position-fixed top-50 start-50 translate-middle" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="me-auto">Notification</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
      Aucun véhicule ne correspond à ce vin!
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
        }

        // Libérer le résultat
        $result->free();
    } else {
        // Erreur lors de l'exécution de la requête
       echo ' <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
   
   
      <script src="../js/bootstrap.min.js"></script>';
   
        echo '<div id="bienvenue-toast" class="toast position-fixed top-50 start-50 translate-middle" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="me-auto">Notification</strong>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        erreur de conneion!
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
      </script>" . $con->error;
    }

    $con->close();
    ?>
    <!-- Ajoutez d'autres balises HTML, scripts, etc. ici -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
