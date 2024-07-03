<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $id = $_POST['id'];

    $sql = "UPDATE chapitres SET description_chapitre=? WHERE id=?";
    $stmt = $connex->prepare($sql);
    $stmt->bind_param("si", $description, $id);
    $stmt->execute();

    header('Location: dashboardFormateur.php');
    exit();
} else {
    $id = $_GET['id'];

    $sql = "SELECT description_chapitre FROM chapitres WHERE id=?";
    $stmt = $connex->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $chapitre = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Description</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="light-theme" id="modifChap">

<?php include 'include/navbar.php'; ?>

<div class="container">
    <h2 class="mt-4">Modifier Description</h2>
    <form action="modifierDescription.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($chapitre['description_chapitre']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
        <a href="dashboardFormateur.php" class="btn btn-warning">Retour</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/theme.js"></script>
</body>
</html>

<?php
$connex->close();
?>
