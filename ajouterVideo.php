<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'include/bsd.php';

    $videoTitre = $connex->real_escape_string($_POST['titre_video']);
    $videoUrl = $connex->real_escape_string($_POST['url_video']);
    $chapId = intval($_POST['chapitre_id']);

    $sqlReq = "INSERT INTO videos (titre_video, url_video, chapitre_id) VALUES ('$videoTitre', '$videoUrl', '$chapId')";

    if ($connex->query($sqlReq) === TRUE) {
        header('Location: dashboardFormateur.php');
        exit();
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
    <title>Ajouter Vidéo</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
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

<!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>

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
