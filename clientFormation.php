<?php
include 'include/bsd.php';

if (!isset($_GET['chapitreId']) || !isset($_GET['token'])) {
    echo "Lien invalide.";
    exit();
}

$chapitreId = $_GET['chapitreId'];
$token = $_GET['token'];
$lien = "clientFormation.php?chapitreId=$chapitreId&token=$token";

$query = "SELECT * FROM lien_temporaire WHERE chapitre_id = ? AND lien = ?";
$stmt = $connex->prepare($query);
$stmt->bind_param("is", $chapitreId, $lien);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Lien invalide ou expiré.";
    exit();
}

$row = $result->fetch_assoc();
$dateExpiration = $row['date_expiration'];

if (new DateTime() > new DateTime($dateExpiration)) {
    echo "Lien expiré.";
    // Supprimer le lien expiré de la base de données
    $deleteQuery = "DELETE FROM lien_temporaire WHERE chapitre_id = ? AND lien = ?";
    $stmtDelete = $connex->prepare($deleteQuery);
    $stmtDelete->bind_param("is", $chapitreId, $lien);
    $stmtDelete->execute();
    exit();
}

$queryChapitre = "SELECT * FROM chapitres WHERE id = ?";
$stmtChapitre = $connex->prepare($queryChapitre);
$stmtChapitre->bind_param("i", $chapitreId);
$stmtChapitre->execute();
$resultChapitre = $stmtChapitre->get_result();
$chapitre = $resultChapitre->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formation</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="light-theme" id="detailsFormation">

<!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>

<div class="container">
    <h2 class="mb-4"><?php echo htmlspecialchars($chapitre['titre_chapitre']); ?></h2>
    <?php
    $reqVideo = "SELECT * FROM videos WHERE chapitre_id = ?";
    $stmtVideo = $connex->prepare($reqVideo);
    $stmtVideo->bind_param("i", $chapitreId);
    $stmtVideo->execute();
    $resultVideo = $stmtVideo->get_result();

    if ($resultVideo->num_rows > 0) {
        while ($video = $resultVideo->fetch_assoc()) {
            echo "<div class='card mb-3'>
                    <div class='card-body'>
                        <h5 class='card-title'>{$video['titre_video']}</h5>
                        <a href='{$video['url_video']}' target='_blank'>{$video['url_video']}</a>
                    </div>
                  </div>";
        }
    } else {
        echo "<p>Aucune vidéo trouvée pour ce chapitre.</p>";
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/theme.js"></script>

</body>
</html>

<?php
$connex->close();
?>
