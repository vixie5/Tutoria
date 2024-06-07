<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>


<body class="light-theme" id="acceuil">

<!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>

<div class="container">
    <!-- Section pres tutoria -->
    <div class="section-title">
        <h2>Présentation de Tutoria</h2>
    </div>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sed dictum est. Cras convallis magna ac urna feugiat, in vulputate purus hendrerit. Phasellus tempor velit in bibendum volutpat. Nullam et eros vitae arcu sagittis tincidunt. Sed euismod, turpis quis vestibulum scelerisque, purus mi blandit eros, at feugiat neque orci ac ex. Integer finibus bibendum ligula, sit amet gravida felis vehicula non.</p>

    <!-- Section formateurs -->
    <div class="section-title">
        <h2>Nos Formateurs</h2>
    </div>
    <div class="row">
        <?php
        // Connexion bsd avec un include
        include 'include/bsd.php';

        // recup formateur
        $sqlReq = "SELECT username, bio, pdp FROM users WHERE role='formateur'";
        $result = $connex->query($sqlReq);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4">';
                echo '<div class="card mb-4">';
                echo '<img src="assets/images/' . htmlspecialchars($row["pdp"]) . '" alt="Photo de profil" class="card-img-top">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($row["username"]) . '</h5>';
                echo '<p class="card-text">' . htmlspecialchars($row["bio"]) . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>Aucun formateur trouvé.</p>";
        }

        $connex->close();
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="assets/js/theme.js"></script>

</body>
</html>
