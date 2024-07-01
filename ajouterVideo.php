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
    $miniaturePath = null;

    if ($_POST['miniature_option'] == 'custom') {
        if (!empty($_FILES['miniature']['name'])) {
            $miniature = $_FILES['miniature'];
            $extension = pathinfo($miniature['name'], PATHINFO_EXTENSION);
            $miniaturePath = 'assets/images/upload/' . preg_replace('/[^A-Za-z0-9\-]/', '_', $videoTitre) . '.' . $extension;
            $uploadPath = 'C:/wamp64/www/projetStage/Tutoria/' . $miniaturePath;

            if (!move_uploaded_file($miniature['tmp_name'], $uploadPath)) {
                echo "Erreur lors de l'upload de la miniature.";
                exit();
            }
        }
    } elseif ($_POST['miniature_option'] == 'url') {
        // Assurez-vous de gérer l'obtention de la miniature à partir de l'URL de la vidéo
        $miniaturePath = getVideoThumbnail($videoUrl);
    }

    $sqlReq = "INSERT INTO videos (titre_video, url_video, chapitre_id, miniature) VALUES ('$videoTitre', '$videoUrl', '$chapId', '$miniaturePath')";

    if ($connex->query($sqlReq) === TRUE) {
        header('Location: dashboardFormateur.php');
        exit();
    } else {
        echo "Erreur : " . $sqlReq . "<br>" . $connex->error;
    }
    $connex->close();
}

function getVideoThumbnail($videoUrl) {
    // Extraire l'ID de la vidéo de YouTube
    if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches)) {
        $videoId = $matches[1];
        return "https://img.youtube.com/vi/$videoId/0.jpg"; // URL de la miniature YouTube
    }
    return null;
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
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="light-theme" id="ajoutvideo">

<!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>

<div class="container">
    <h2>Ajouter Vidéo</h2>
    <form action="ajouterVideo.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titre_video" class="form-label">Titre de la Vidéo</label>
            <input type="text" class="form-control" id="titre_video" name="titre_video" required>
        </div>
        <div class="mb-3">
            <label for="url_video" class="form-label">URL de la Vidéo</label>
            <input type="text" class="form-control" id="url_video" name="url_video" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Miniature de la Vidéo</label><br>
            <input type="radio" id="miniature_url" name="miniature_option" value="url" checked>
            <label for="miniature_url">Utiliser la miniature de l'URL de la vidéo</label><br>
            <input type="radio" id="miniature_custom" name="miniature_option" value="custom">
            <label for="miniature_custom">Télécharger une miniature personnalisée</label>
            <input type="file" class="form-control mt-2" id="miniature" name="miniature" accept="image/*">
        </div>
        <input type="hidden" name="chapitre_id" value="<?php echo intval($_GET['chapitre_id']); ?>">
        <button type="submit" class="btn btn-primary">Ajouter Vidéo</button>
        <a href="dashboardFormateur.php" class="btn btn-warning">Retour</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/theme.js"></script>

</body>
</html>
