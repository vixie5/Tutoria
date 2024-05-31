<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'adminTutoria') {
    header('Location: index.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutoria";

$connex = new mysqli($servername, $username, $password, $dbname);
if ($connex->connect_error) {
    die("Échec de la connexion : " . $connex->connect_error);
}

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = intval($_POST['user_id']);
    
    $requete = $connex->prepare("DELETE FROM users WHERE id = ?");
    $requete->bind_param("i", $userId);
    
    if ($requete->execute()) {
        $successMessage = "Utilisateur supprimé avec succès";
    } else {
        $errorMessage = "Erreur lors de la suppression de l'utilisateur : " . $connex->error;
    }

    $requete->close();
}
$connex->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link rel="icon" type="image/jpg" href="image/logoTutoria">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #1c1c1c;
            color: white;
            padding-top: 120px;
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
        .delete-container {
            background-color: #e0f7ff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin: 0 auto;
        }
        .form-control {
            margin-bottom: 15px;
            border-radius: 25px;
            padding: 10px 20px;
        }
        .btn {
            width: 100%;
            border-radius: 25px;
            padding: 10px;
            margin-top: 10px;
            transition: all 0.3s ease-in-out;
        }
        .btn-danger, .btn-primary {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-danger:hover, .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }
        .delete-container h3 {
            color: black;
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

<div class="delete-container">
    <h3>Delete User</h3>
    <form method="POST" action="">
        <input type="number" class="form-control" id="user_id" name="user_id" placeholder="ID user" required>
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
    <button onclick="window.location.href='tableauBord.php'" class="btn btn-primary">Accueil</button>
    <?php if ($successMessage): ?>
        <div class="alert alert-success mt-3"><?php echo $successMessage; ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="alert alert-danger mt-3"><?php echo $errorMessage; ?></div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
