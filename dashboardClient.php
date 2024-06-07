<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

$clientId = $_SESSION['user_id'];

// Récupérer les chapitres associés au client
$sqlChapitres = "SELECT chapitres.titre_chapitre FROM chapitres
                JOIN formations_clients ON chapitres.formateur_id = formations_clients.formateur_id
                WHERE formations_clients.client_id = ?";
$stmtChapitres = $connex->prepare($sqlChapitres);
$stmtChapitres->bind_param("i", $clientId);
$stmtChapitres->execute();
$resultChapitres = $stmtChapitres->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="light-theme" id="dashboardClient">

<!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>


<div class="container mt-5">
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h2>
    <h3>Vos Formations</h3>
    <?php if ($resultChapitres->num_rows > 0): ?>
        <ul class="list-group">
            <?php while($chapitre = $resultChapitres->fetch_assoc()): ?>
                <li class="list-group-item">
                    <h3><?php echo htmlspecialchars($chapitre['titre_chapitre']); ?></h3>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Aucun chapitre disponible pour le moment.</p>
    <?php endif; ?>
</div>

<!-- Inclure les scripts Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    // Déconnexion
function deconnexion() {
    window.location.href = "deconnexion.php";
}

// Changement de thème
function setLightTheme() {
    document.body.className = 'light-theme';
    setCookie('theme', 'light-theme', 7);
}

function setDarkTheme() {
    document.body.className = 'dark-theme';
    setCookie('theme', 'dark-theme', 7);
}

function applyTheme() {
    const theme = getCookie('theme');
    if (theme === 'dark-theme') {
        setDarkTheme();
    } else {
        setLightTheme();
    }
}

</script>

</body>
</html>

<?php
// Fermer les déclarations préparées et la connexion
$stmtChapitres->close();
$connex->close();
?>
