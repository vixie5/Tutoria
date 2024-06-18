<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chapitreId = $_POST['chapitreId'];
    $expirationDate = $_POST['expirationDate'];

    $lien = "clientFormation.php?chapitreId=$chapitreId&token=" . bin2hex(random_bytes(16));

    $formateurId = $connex->query("SELECT id FROM users WHERE username='{$_SESSION['username']}'")->fetch_assoc()['id'];

    $stmt = $connex->prepare("INSERT INTO lien_temporaire (formateur_id, chapitre_id, date_expiration, lien) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $formateurId, $chapitreId, $expirationDate, $lien);

    if ($stmt->execute()) {
        echo "Lien généré avec succès : <a href='$lien'>$lien</a>";
    } else {
        echo "Erreur lors de la génération du lien : " . $stmt->error;
    }

    $stmt->close();
    $connex->close();
}
?>
