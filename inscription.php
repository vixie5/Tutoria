<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #e0e7ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .signup-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .signup-container img {
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
        .form-check-label {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="signup-container">
<img src="assets/images/logoTutoria.png" alt="Logo">
    <h3>Inscription</h3>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="text" class="form-control" id="username" name="username" placeholder="Nom d'utilisateur" required>
        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
        
        <div class="form-check">
            <input class="form-check-input" type="radio" name="role" id="formateur" value="formateur" required>
            <label class="form-check-label" for="formateur">Formateur</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="role" id="client" value="client" required>
            <label class="form-check-label" for="client">Client</label>
        </div>
        
        <div id="formateur-extra-fields" style="display: none;">
            <textarea class="form-control" id="bio" name="bio" placeholder="Biographie"></textarea>
            <input type="file" class="form-control" id="pdp" name="pdp">
        </div>
        
        <button type="submit" class="btn btn-primary">Inscription</button>
    </form>
    <p class="mt-3">Vous avez déjà un compte ? <a href="connexion.php">Connexion</a></p>
</div>

<?php
include 'include/bsd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $bio = isset($_POST['bio']) ? $_POST['bio'] : '';
    $pdp = '';

    if ($role == 'formateur' && isset($_FILES['pdp'])) {
        $repertoire = "C:/wamp64/www/projetStage/Tutoria/assets/images/upload/";
        $typeImg = strtolower(pathinfo($_FILES["pdp"]["name"], PATHINFO_EXTENSION));
        $newName = $username . '.' . $typeImg;
        $fichier = $repertoire . $newName;
        if (move_uploaded_file($_FILES["pdp"]["tmp_name"], $fichier)) {
            $pdp = $newName;
        } else {
            echo "<div class='alert alert-danger mt-3'>Désolé, une erreur s'est produite lors du téléchargement de votre fichier.</div>";
        }
    }

    $checkReq = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $resultat = $connex->query($checkReq);

    if ($resultat->num_rows > 0) {
        echo "<div class='alert alert-danger mt-3'>Cet utilisateur existe déjà.</div>";
    } else {
        $sqlReq = "INSERT INTO users (username, email, password, role, bio, pdp) VALUES ('$username','$email','$password', '$role', '$bio', '$pdp')";
        if ($connex->query($sqlReq) === TRUE) {
            if ($role == 'formateur') {
                header("Location: dashboardFormateur.php");
            } else {
                header("Location: dashboardClient.php");
            }
            exit();
        } else {
            echo "<div class='alert alert-danger mt-3'>Erreur : " . $sqlReq . "<br>" . $connex->error . "</div>";
        }
    }

    $connex->close();
}
?>

<script>
    document.querySelectorAll('input[name="role"]').forEach(function(elem) {
        elem.addEventListener('change', function() {
            if (this.value === 'formateur') {
                document.getElementById('formateur-extra-fields').style.display = 'block';
            } else {
                document.getElementById('formateur-extra-fields').style.display = 'none';
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>