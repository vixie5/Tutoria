<?php
    $servername = "localhost";
    $username = "c1sdxprojects";
    $password = "naKzTZL6F!2ez";
    $dbname = "c1sdxtutoria";

    $connex = new mysqli($servername, $username, $password, $dbname);

    if ($connex->connect_error) {
        die("Échec de la connexion : " . $connex->connect_error);
    }
?>