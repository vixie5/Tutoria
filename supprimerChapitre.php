<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutoria";

$connex = new mysqli($servername, $username, $password, $dbname);

if ($connex->connect_error) {
    die("Échec de la connexion : " . $connex->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sqlReq = "DELETE FROM videos WHERE chapitre_id='$id'";
    $connex->query($sqlReq);

    $sqlReq = "DELETE FROM chapitres WHERE id='$id'";
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
