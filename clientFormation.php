<?php
include 'include/bsd.php';

if (!isset($_GET['chapitreId']) || !isset($_GET['token'])) {
    echo "Lien invalide.";
    exit();
}

$chapitreId = $_GET['chapitreId'];
$token = $_GET['token'];

$sqlVerifyLink = "SELECT * FROM lien_temporaire WHERE chapitre_id = ? AND lien LIKE ? AND date_expiration >= CURDATE()";
$stmtVerifyLink = $connex->prepare($sqlVerifyLink);
$tokenPattern = "%$token%";
$stmtVerifyLink->bind_param("is", $chapitreId, $tokenPattern);
$stmtVerifyLink->execute();
$resultVerifyLink = $stmtVerifyLink->get_result();

if ($resultVerifyLink->num_rows === 0) {
    echo "Lien invalide ou expiré.";
    exit();
}

$sqlChapitre = "SELECT titre_chapitre FROM chapitres WHERE id = ?";
$stmtChap = $connex->prepare($sqlChapitre);
$stmtChap->bind_param("i", $chapitreId);
$stmtChap->execute();
$resultatChap = $stmtChap->get_result();
$chapitre = $resultatChap->fetch_assoc();

$sqlVideos = "SELECT id, titre_video, url_video FROM videos WHERE chapitre_id = ?";
$stmtVideos = $connex->prepare($sqlVideos);
$stmtVideos->bind_param("i", $chapitreId);
$stmtVideos->execute();
$resultatVideos = $stmtVideos->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Formation</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
</head>

<body class="light-theme" id="detailsFormation">

<?php include 'include/navbar.php'; ?>

<div class="container-fluid h-100">
    <div class="row h-100">
        <nav class="col-md-3 sidebar">
            <?php if ($resultatVideos->num_rows > 0): ?>
                <?php while($video = $resultatVideos->fetch_assoc()): ?>
                    <div class="video-item" onclick="changeVideo('<?php echo htmlspecialchars($video['url_video']); ?>')">
                        <h4 class="video-title"><?php echo htmlspecialchars($video['titre_video']); ?></h4>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Aucune vidéo disponible pour ce chapitre.</p>
            <?php endif; ?>
        </nav>
        <main class="col-md-9 video-container">
            <div class="content-header">
                <h2><?php echo htmlspecialchars($chapitre['titre_chapitre']); ?></h2>
            </div>
            <div class="iframe-container">
                <iframe id="videoPlayer" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/theme.js"></script>
<script src="assets/js/videoLec.js"></script>

</body>
</html>

<?php
$stmtChap->close();
$stmtVideos->close();
$connex->close();
?>