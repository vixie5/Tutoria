<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $formateurUsername = $_SESSION['username'];

    $sql = "DELETE fc FROM formations_clients fc
            JOIN users u ON fc.client_id = u.id
            WHERE u.email = ? AND fc.formateur_id = (SELECT id FROM users WHERE username = ?)";
    $stmt = $connex->prepare($sql);
    $stmt->bind_param("ss", $email, $formateurUsername);
    
    if ($stmt->execute()) {
        header('Location: dashboardFormateur.php');
    } else {
        echo "Erreur lors de la suppression de l'accès permanent.";
    }

    $stmt->close();
} else {
    echo "Email non spécifié.";
}

$connex->close();
?>