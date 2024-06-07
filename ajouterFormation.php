<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'include/bsd.php';


    $titreFormation = $_POST['titre_chapitre'];
    $formateurId = $_SESSION['user_id'];

    $sqlReq = "INSERT INTO chapitres (titre_chapitre, formateur_id) VALUES ('$titreFormation', '$formateurId')";

    if ($connex->query($sqlReq) === TRUE) {
        header('Location: dashboardFormateur.php');
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
    <title>Ajouter Formation</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="light-theme" id="ajoutFormation">

<!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>

<div class="container">
    <h2>Ajouter Formation</h2>
    <form action="ajouterFormation.php" method="POST">
        <div class="mb-3">
            <label for="titre_chapitre" class="form-label">Titre de la formation</label>
            <input type="text" class="form-control" id="titre_chapitre" name="titre_chapitre" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter Formation</button>
        <a href="dashboardFormateur.php" class="btn btn-warning">Retour</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/theme.js"></script>

</body>
</html>
