<?php
session_start();

// info identification
$adminUsername = 'adminTutoria';
$adminPassword = 'projetTutoria';

// verifier user valide
if (isset($_SESSION['user_id']) && $_SESSION['username'] === $adminUsername) {
    header('Location: tableauBord.php');
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verif id user admin
    if ($username === $adminUsername && $password === $adminPassword) {
        $_SESSION['user_id'] = session_id();
        $_SESSION['username'] = $adminUsername;
        // $_SESSION['role'] = 'admin';

        header('Location: tableauBord.php');
        exit;
    } else {
        $error = 'Identifiants incorrects.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e0e7ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .form-control {
            margin-bottom: 15px;
            border-radius: 25px;
            padding: 10px 20px;
        }
        .btn-primary {
            width: 100%;
            border-radius: 25px;
            padding: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h3>Connexion Administrateur</h3>
    <form method="POST" action="">
        <input type="text" class="form-control" id="username" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
    <?php if ($error): ?>
        <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
