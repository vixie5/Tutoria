<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'adminTutoria') {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes</title>
    <link rel="icon" type="image/jpg" href="image/logoTutoria">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #1c1c1c;
            color: white;
            padding-top: 90px; /* Plus grand espace en haut */
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand img {
            width: 40px;
            height: 40px;
        }
        .navbar-nav .nav-link {
            color: white !important;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s;
        }
        .navbar-nav .nav-link:hover {
            background-color: #007bff;
        }
        .container {
            text-align: center;
        }
        .folder {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: #007bff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            margin: 20px auto;
            cursor: pointer;
            color: white;
            font-size: 18px;
            font-weight: 500;
            width: 300px;
            height: 60px;
            position: relative;
            text-align: center;
        }
        .folder::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            background-color: #0056b3;
            clip-path: polygon(0 0, 100% 0, 0 100%);
        }
        h1 {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="image/logoTutoria" alt="Logo">
        </a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link btn btn-primary" href="tableauBord.php">Accueil</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1>Commandes</h1>
    <div class="folder" onclick="window.location.href='deleteUser.php'">
        Delete User
    </div>
    <!-- Ajoutez d'autres sous-dossiers ici -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
