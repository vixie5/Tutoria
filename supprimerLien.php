<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

if (isset($_GET['id'])) {
    $lienId = $_GET['id'];
    $formateurUsername = $_SESSION['username'];

    $sql = "DELETE FROM lien_temporaire WHERE id = ? AND formateur_id = (SELECT id FROM users WHERE username = ?)";
    $stmt = $connex->prepare($sql);
    $stmt->bind_param("is", $lienId, $formateurUsername);
    
    if ($stmt->execute()) {
        header('Location: dashboardFormateur.php');
    } else {
        echo "Erreur lors de la suppression du lien.";
    }

    $stmt->close();
} else {
    echo "ID du lien non spécifié.";
}

$connex->close();
?>