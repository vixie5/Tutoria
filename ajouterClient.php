<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'formateur') {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'include/bsd.php';

    $formateurId = $_SESSION['user_id'];
    $clientId = $_POST['client_id'];

    $sqlReq = "INSERT INTO formations_clients (formateur_id, client_id) VALUES ('$formateurId', '$clientId')";

    if ($connex->query($sqlReq) === TRUE) {
        header('Location: dashboardFormateur.php');
    } else {
        echo "Erreur : " . $sqlReq . "<br>" . $connex->error;
    }

    $connex->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Client</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .navbar {
            background-color: #fff;
        }
        .navbar-brand, .nav-link {
            color: #000 !important;
        }
        .container {
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>

<div class="container">
    <h2>Ajouter Client</h2>
    <form action="ajouterClient.php" method="POST">
        <div class="mb-3">
            <label for="client_id" class="form-label">ID du Client</label>
            <input type="number" class="form-control" id="client_id" name="client_id" required>
        </div>
        <button type="submit" class="btn btn-success">Ajouter Client</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
