<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

$clientId = $_SESSION['user_id'];

// Récupérer les chapitres associés au client
$sqlChapitres = "SELECT chapitres.id as chapitre_id, chapitres.titre_chapitre, 
                 COALESCE(MIN(videos.miniature), '') as miniature 
                 FROM chapitres
                 JOIN formations_clients ON chapitres.formateur_id = formations_clients.formateur_id
                 LEFT JOIN videos ON chapitres.id = videos.chapitre_id
                 WHERE formations_clients.client_id = ?
                 GROUP BY chapitres.id, chapitres.titre_chapitre";
$stmtChapitres = $connex->prepare($sqlChapitres);
$stmtChapitres->bind_param("i", $clientId);
$stmtChapitres->execute();
$resultatChapitres = $stmtChapitres->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="light-theme" id="dashboardClient">

<!-- Navbar avec un include -->
<?php include 'include/navbar.php'; ?>

<div class="container mt-5">
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h2>
    <div class="titreForma">
    <h3>Voici vos formations</h3>
    </div>
    <?php if ($resultatChapitres->num_rows > 0): ?>
        <div class="folder-list">
            <?php while($chapitre = $resultatChapitres->fetch_assoc()): ?>
                <div class="folder" style="background-image: url('<?php echo htmlspecialchars($chapitre['miniature']); ?>');" onclick="location.href='detailsFormation.php?chapitre_id=<?php echo htmlspecialchars($chapitre['chapitre_id']); ?>'">
                    <div class="title"><?php echo htmlspecialchars($chapitre['titre_chapitre']); ?></div>
                    <div class="inverse-title"><?php echo htmlspecialchars($chapitre['titre_chapitre']); ?></div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Aucun chapitre disponible pour le moment.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/theme.js"></script>

</body>
</html>

<?php
$stmtChapitres->close();
$connex->close();
?>