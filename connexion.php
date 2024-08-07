<?php
session_start();

include 'include/bsd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sqlReq = "SELECT * FROM users WHERE username='$username'";
    $resultat = $connex->query($sqlReq);

    if ($resultat->num_rows > 0) {
        $row = $resultat->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['user_id'] = $row['id'];

            if ($row['role'] == 'formateur') {
                header("Location: dashboardFormateur.php");
            } else if ($row['role'] == 'client') {
                header("Location: dashboardClient.php");
            }
            exit();
        } else {
            echo "<div class='alert alert-danger mt-3'>Mot de passe invalide.</div>";
        }
    } else {
        echo "<div class='alert alert-danger mt-3'>Aucun utilisateur trouvé avec ce nom d'utilisateur.</div>";
    }

    $connex->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        .login-container img {
            width: 80px;
            margin-bottom: 20px;
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
        .link-container {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <img src="assets/images/logoTutoria.png" alt="Logo">
    <h3>Connexion</h3>
    <form method="POST" action="">
        <input type="text" class="form-control" id="username" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
    <div class="link-container">
        <a href="inscription.php">Inscription</a>
        <a href="#" id="forgotPassword">Mot de passe oublié ?</a>
    </div>
</div>

<!-- Modal pour le mot de passe oublié -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Réinitialisation du mot de passe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="forgotPasswordForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#forgotPassword').click(function(e) {
        e.preventDefault();
        $('#forgotPasswordModal').modal('show');
    });

    $('#forgotPasswordForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'reset_password.php',
            method: 'POST',
            data: { email: $('#email').val() },
            success: function(response) {
                alert(response);
                $('#forgotPasswordModal').modal('hide');
            },
            error: function() {
                alert('Une erreur est survenue. Veuillez réessayer.');
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>