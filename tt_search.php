<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de la recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php
    session_start();
    require_once("param.inc.php");

    $vin = htmlentities($_POST['vin']);
    $con = new mysqli($host, $login, $passwd, $dbname);

    if ($con->connect_error) {
        echo '<div class="alert alert-danger" role="alert">Erreur de connexion à la base de données : ' . $con->connect_error . '</div>';
        exit();
    }

    $result = $con->query("SELECT date_heure, vin, kilometrage, info_complementaires, nom, prenom FROM vehicule INNER JOIN vendeur ON vehicule.idV = vendeur.id WHERE vin = '$vin'");

    if ($result) {
        if ($result->num_rows > 0) {
            echo '<h2>Véhicule Recherché</h2>';
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<table class="table table-striped mx-auto" style="color: black;">';
            echo '<thead class="thead-dark"><tr>
                    <th>Date et Heure</th>
                    <th>Vin</th> 
                    <th>Kilométrage</th>
                    <th>Informations Complémentaires</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                  </tr></thead>';
            echo '<tbody>';

            $timestamps = [];
            $kilometrages = [];

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['date_heure'] . '</td>';
                echo '<td>' . $row['vin'] . '</td>';
                echo '<td>' . $row['kilometrage'] . '</td>';
                echo '<td>' . $row['info_complementaires'] . '</td>';
                echo '<td>' . $row['nom'] . '</td>';
                echo '<td>' . $row['prenom'] . '</td>';
                echo '</tr>';

                $timestamps[] = $row['date_heure'];
                $kilometrages[] = $row['kilometrage'];
            }

            echo '</tbody></table>';
            echo '</div>';
            echo '<div class="col-md-6">';
            echo '<canvas id="kilometrageChart"></canvas>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="toast-container position-fixed top-50 start-50 translate-middle p-3">';
            echo '<div id="no-vehicle-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">';
            echo '<div class="toast-header">';
            echo '<strong class="me-auto">Notification</strong>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>';
            echo '</div>';
            echo '<div class="toast-body">Aucun véhicule ne correspond à ce VIN!</div>';
            echo '</div>';
            echo '</div>';

            echo "<script>
                    setTimeout(function () {
                        var toast = new bootstrap.Toast(document.getElementById('no-vehicle-toast'));
                        toast.show();
                    }, 200);
                    setTimeout(function () {
                        window.location.href = 'index.php';
                    }, 4000);
                  </script>";
        }
        $result->free();
    } else {
        echo '<div class="toast-container position-fixed top-50 start-50 translate-middle p-3">';
        echo '<div id="error-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">';
        echo '<div class="toast-header">';
        echo '<strong class="me-auto">Notification</strong>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>';
        echo '</div>';
        echo '<div class="toast-body">Erreur lors de l\'exécution de la requête : ' . $con->error . '</div>';
        echo '</div>';
        echo '</div>';

        echo "<script>
                setTimeout(function () {
                    var toast = new bootstrap.Toast(document.getElementById('error-toast'));
                    toast.show();
                }, 200);
                setTimeout(function () {
                    window.location.href = 'index.php';
                }, 4000);
              </script>";
    }

    $con->close();
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('kilometrageChart').getContext('2d');
            var kilometrageChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($timestamps); ?>,
                    datasets: [{
                        label: 'Kilométrage',
                        data: <?php echo json_encode($kilometrages); ?>,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1,
                        fill: true,
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            title: {
                                display: true,
                                text: 'Date et Heure'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Kilométrage'
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
