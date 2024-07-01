<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

if (isset($_POST['submit'])) {
    $id = intval($_POST['id']);
    $videoTitre = $connex->real_escape_string($_POST['titre_video']);
    $videoUrl = $connex->real_escape_string($_POST['url_video']);

    $miniaturePath = null;
    if ($_POST['miniature_option'] == 'custom') {
        if (!empty($_FILES['miniature']['name'])) {
            // Upload de la nouvelle miniature
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

    if ($miniaturePath) {
        $videoUp = "UPDATE videos SET titre_video='$videoTitre', url_video='$videoUrl', miniature='$miniaturePath' WHERE id='$id'";
    } else {
        $videoUp = "UPDATE videos SET titre_video='$videoTitre', url_video='$videoUrl' WHERE id='$id'";
    }

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
    <title>Modifier Vidéo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="light-theme" id="modifVideo">

<!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>

<div class="container">
    <h2 class="my-4">Modifier Vidéo</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $video['id']; ?>">
        <div class="mb-3">
            <label for="titre_video" class="form-label">Titre de la Vidéo</label>
            <input type="text" class="form-control" id="titre_video" name="titre_video" value="<?php echo htmlspecialchars($video['titre_video']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="url_video" class="form-label">URL de la Vidéo</label>
            <input type="url" class="form-control" id="url_video" name="url_video" value="<?php echo htmlspecialchars($video['url_video']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Miniature de la Vidéo</label><br>
            <input type="radio" id="miniature_url" name="miniature_option" value="url" <?php if (empty($video['miniature'])) echo 'checked'; ?>>
            <label for="miniature_url">Utiliser la miniature de l'URL de la vidéo</label><br>
            <input type="radio" id="miniature_custom" name="miniature_option" value="custom" <?php if (!empty($video['miniature'])) echo 'checked'; ?>>
            <label for="miniature_custom">Télécharger une miniature personnalisée</label>
            <?php if (!empty($video['miniature'])): ?>
                <img src="<?php echo htmlspecialchars($video['miniature']); ?>" alt="Miniature actuelle" class="img-fluid mb-2">
            <?php endif; ?>
            <input type="file" class="form-control mt-2" id="miniature" name="miniature" accept="image/*">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Modifier</button>
        <a href="dashboardFormateur.php" class="btn btn-warning">Retour</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/theme.js"></script>

</body>
</html>
