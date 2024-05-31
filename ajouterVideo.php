<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutoria";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connex = new mysqli($servername, $username, $password, $dbname);

    if ($connex->connect_error) {
        die("Échec de la connexion : " . $connex->connect_error);
    }

    $videoTitre = $connex->real_escape_string($_POST['titre_video']);
    $videoUrl = $connex->real_escape_string($_POST['url_video']);
    $chapId = intval($_POST['chapitre_id']);

    $sql = "INSERT INTO videos (titre_video, url_video, chapitre_id) VALUES ('$videoTitre', '$videoUrl', '$chapId')";

    if ($connex->query($sql) === TRUE) {
        header('Location: dashboardFormateur.php');
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $connex->error;
    }
    $connex->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Vidéo</title>
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
    <h2>Ajouter Vidéo</h2>
    <form action="ajouterVideo.php" method="POST">
        <div class="mb-3">
            <label for="titre_video" class="form-label">Titre de la Vidéo</label>
            <input type="text" class="form-control" id="titre_video" name="titre_video" required>
        </div>
        <div class="mb-3">
            <label for="url_video" class="form-label">URL de la Vidéo</label>
            <input type="text" class="form-control" id="url_video" name="url_video" required>
        </div>
        <input type="hidden" name="chapitre_id" value="<?php echo intval($_GET['chapitre_id']); ?>">
        <button type="submit" class="btn btn-success">Ajouter Vidéo</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
