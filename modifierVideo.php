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

$connex = new mysqli($servername, $username, $password, $dbname);

if ($connex->connect_error) {
    die("Échec de la connexion : " . $connex->connect_error);
}

if (isset($_POST['submit'])) {
    $id = intval($_POST['id']);
    $videoTitre = $connex->real_escape_string($_POST['titre_video']);
    $videoUrl = $connex->real_escape_string($_POST['url_video']);

    $videoUp = "UPDATE videos SET titre_video='$videoTitre', url_video='$videoUrl' WHERE id='$id'";
    if ($connex->query($videoUp) === TRUE) {
        header('Location: dashboardFormateur.php');
        exit();
    } else {
        echo "Erreur : " . $connex->error;
    }
} else if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sqlmodifVideo = "SELECT * FROM videos WHERE id='$id'";
    $resultat = $connex->query($sqlmodifVideo);

    if ($resultat->num_rows > 0) {
        $video = $resultat->fetch_assoc();
    } else {
        echo "Vidéo non trouvée";
        exit();
    }
} else {
    header('Location: dashboardFormateur.php');
    exit();
}

$connex->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Vidéo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="my-4">Modifier Vidéo</h2>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $video['id']; ?>">
        <div class="mb-3">
            <label for="titre_video" class="form-label">Titre de la Vidéo</label>
            <input type="text" class="form-control" id="titre_video" name="titre_video" value="<?php echo htmlspecialchars($video['titre_video']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="url_video" class="form-label">URL de la Vidéo</label>
            <input type="url" class="form-control" id="url_video" name="url_video" value="<?php echo htmlspecialchars($video['url_video']); ?>" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Modifier</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
