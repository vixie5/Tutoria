<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .modern-section {
            background-color: var(--card-bg-color);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 40px;
        }
        .modern-section h2 {
            color: var(--text-color);
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        .modern-section h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: var(--link-color);
        }
        .formateur-card {
            background-color: var(--bg-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }
        .formateur-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .formateur-card h5 {
            color: var(--text-color);
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .formateur-card p {
            color: var(--text-color);
            opacity: 0.8;
            font-size: 0.9rem;
        }
    </style>
</head>

<body class="light-theme" id="acceuil">

<?php include 'include/navbar.php'; ?>

<div class="container">
    <div class="modern-section">
        <h2>Présentation de Tutoria</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sed dictum est. Cras convallis magna ac urna feugiat, in vulputate purus hendrerit. Phasellus tempor velit in bibendum volutpat. Nullam et eros vitae arcu sagittis tincidunt. Sed euismod, turpis quis vestibulum scelerisque, purus mi blandit eros, at feugiat neque orci ac ex. Integer finibus bibendum ligula, sit amet gravida felis vehicula non.</p>
    </div>

    <div class="modern-section">
        <h2>Nos derniers Formateurs</h2>
        <div class="row">
            <?php
            include 'include/bsd.php';

            $sqlReq = "SELECT id, username, bio, pdp FROM users WHERE role='formateur' ORDER BY id DESC LIMIT 6";
            $result = $connex->query($sqlReq);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<a href="detailFormateurs.php?id=' . $row['id'] . '" class="text-decoration-none">';
                    echo '<div class="formateur-card">';
                    echo '<img src="assets/images/upload/' . htmlspecialchars($row["pdp"]) . '" alt="Photo de profil" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">';
                    echo '<h5>' . htmlspecialchars($row["username"]) . '</h5>';
                    echo '<p>' . htmlspecialchars($row["bio"]) . '</p>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>Aucun formateur trouvé.</p>";
            }

            $connex->close();
            ?>
        </div>
    </div>
    
<div class="text-center mt-4 mb-5">
    <a href="formateurs.php" class="btn btn-outline-primary mb-4">
          Voir tous les formateurs <i class="fas fa-arrow-right"></i> 
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="assets/js/theme.js"></script>

</body>
</html>