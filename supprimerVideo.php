<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sqlReq = "DELETE FROM videos WHERE id='$id'";
    if ($connex->query($sqlReq) === TRUE) {
        $theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
        header("Location: dashboardFormateur.php?theme=$theme");
        exit();
    } else {
        echo "Erreur : " . $connex->error;
    }
}

$connex->close();
?>
