<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tutoria";

    $connex = new mysqli($servername, $username, $password, $dbname);

    if ($connex->connect_error) {
        die("Échec de la connexion : " . $connex->connect_error);
    }
?>