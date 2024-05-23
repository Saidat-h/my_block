<?php
// Récupérer les données POST
$input = file_get_contents('php://input');
$vehicleData = json_decode($input, true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données des Véhicules</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <table>
            <thead>
                <tr>
                 
                    <th>Année</th>
                    <th>Kilométrage</th>
        
                </tr>
            </thead>
            <tbody>
                <?php if ($vehicleData && is_array($vehicleData)): ?>
                    <?php foreach ($vehicleData as $vehicle): ?>
                        <tr>
                    
                            <td><?php echo htmlspecialchars($vehicle['year']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['mileage']); ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aucune donnée disponible</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>