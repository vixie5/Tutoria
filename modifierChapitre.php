<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

if (isset($_POST['submit'])) {
    $id = intval($_POST['id']);
    $chapId = $connex->real_escape_string($_POST['titre_chapitre']);

    $sqlReq = "UPDATE chapitres SET titre_chapitre='$chapId' WHERE id='$id'";
    if ($connex->query($sqlReq) === TRUE) {
        header('Location: dashboardFormateur.php');
        exit();
    } else {
        echo "Erreur : " . $connex->error;
    }
} else if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sqlmodifChapitre = "SELECT * FROM chapitres WHERE id='$id'";
    $resultat = $connex->query($sqlmodifChapitre);

    if ($resultat->num_rows > 0) {
        $chapitre = $resultat->fetch_assoc();
    } else {
        echo "Chapitre non trouvé.";
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
    <title>Modifier Chapitre</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="light-theme" id="modifChap">

    <!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>

<div class="container">
    <h2 class="my-4">Modifier Formation</h2>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $chapitre['id']; ?>">
        <div class="mb-3">
            <label for="titre_chapitre" class="form-label">Titre du Chapitre</label>
            <input type="text" class="form-control" id="titre_chapitre" name="titre_chapitre" value="<?php echo htmlspecialchars($chapitre['titre_chapitre']); ?>" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Modifier</button>
        <a href="dashboardFormateur.php" class="btn btn-warning">Retour</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/theme.js"></script>

</body>
</html>
