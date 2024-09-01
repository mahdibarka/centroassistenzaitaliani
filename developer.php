<?php
$servername = "localhost";
$username = "root"; // Changez avec votre nom d'utilisateur de base de données
$password = ""; // Changez avec votre mot de passe de base de données
$dbname = "centro_assistenza_italiani";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection échouée : " . $conn->connect_error);
}

// Gestion de la suppression des messages
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM contacts WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo "<div class='alert alert-success'>Message supprimé avec succès.</div>";
}

// Récupération des données
$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation des Messages de Contact</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .alert-success {
            padding: 20px;
            background-color: #4CAF50; /* Fond vert */
            color: white;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .alert-error {
            padding: 20px;
            background-color: #f44336; /* Fond rouge */
            color: white;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .btn-delete {
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Consultation des Messages de Contact</h1>

        <a href="developer.php" class="btn btn-primary">Rafraîchir</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom Complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td><a href='developer.php?delete=" . $row['id'] . "' class='btn-delete'>Supprimer</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Aucun message trouvé</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>
