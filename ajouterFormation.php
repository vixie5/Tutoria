<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tutoria";

    $connex = new mysqli($servername, $username, $password, $dbname);

    if ($connex->connect_error) {
        die("Échec de la connexion : " . $connex->connect_error);
    }

    $titreFormation = $_POST['titre_chapitre'];
    $formateurId = $_SESSION['user_id'];

    $sqlReq = "INSERT INTO chapitres (titre_chapitre, formateur_id) VALUES ('$titreFormation', '$formateurId')";

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
    <title>Ajouter Formation</title>
    <link rel="icon" type="image/png" href="images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="images/logoTutoria.png" alt="Logo" width="155" height="50">
        </a>
    </div>
</nav>

<div class="container">
    <h2>Ajouter Formation</h2>
    <form action="ajouterFormation.php" method="POST">
        <div class="mb-3">
            <label for="titre_chapitre" class="form-label">Titre de la formation</label>
            <input type="text" class="form-control" id="titre_chapitre" name="titre_chapitre" required>
        </div>
        <button type="submit" class="btn btn-success">Ajouter Formation</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
