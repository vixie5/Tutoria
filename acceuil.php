bbbbbbbb
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="icon" type="image/png" href="images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <style>
        .light-theme {
            --bg-color: #f0f8ff;
            --text-color: #000;
            --navbar-bg: #ffffff;
            --navbar-text-color: #000;
            --link-color: #ff6347;
            --link-hover-color: #4682b4;
            --card-bg-color: #fff;
            --card-border-color: #ddd;
            --flash-sale-bg: #ffd700;
            --new-label-bg: #9370db;
            --card-shadow: rgba(0, 0, 0, 0.1);
            --badge-color: #fff;
        }
        .dark-theme {
            --bg-color: #2b2b2b;
            --text-color: #f0f8ff;
            --navbar-bg: #333;
            --navbar-text-color: #f0f8ff;
            --link-color: #87cefa;
            --link-hover-color: #b22222;
            --card-bg-color: #3a3a3a;
            --card-border-color: #444;
            --flash-sale-bg: #ffd700;
            --new-label-bg: #9370db;
            --card-shadow: rgba(255, 255, 255, 0.1);
            --badge-color: #f0f8ff;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        .navbar {
            background-color: var(--navbar-bg);
        }
        .navbar-brand,
        .nav-link {
            color: var(--navbar-text-color) !important;
        }
        .nav-link:hover {
            color: var(--link-hover-color) !important;
        }
        .dropdown-menu {
            background-color: var(--navbar-bg);
        }
        .dropdown-item {
            color: var(--navbar-text-color) !important;
        }
        .dropdown-item:hover {
            background-color: var(--link-hover-color) !important;
            color: var(--text-color) !important;
        }
        .vr {
            border-left: 1px solid var(--navbar-text-color);
            height: 40px;
            margin: 0 15px;
        }
        .container {
            margin-top: 40px;
        }
        .section-title {
            margin: 40px 0 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px var(--card-shadow);
            border: 1px solid var(--card-border-color);
            background-color: var(--card-bg-color);
            position: relative;
            overflow: hidden;
        }
        .card-body {
            background-color: var(--card-bg-color);
        }
        .badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 5px 10px;
            border-radius: 5px;
            color: var(--badge-color);
        }
        .dark-theme .card-title,
        .dark-theme .card-text {
            color: #f0f8ff;
        }
        .card-img-top {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .card-title, .card-text {
            text-align: center;
        }
        .card-text {
            margin-top: 30px; /* espace img txt*/
        }
    </style>
</head>
<body class="light-theme">

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="images/tutoria.png" alt="Logo" width="155" height="50" class="d-inline-block align-text-top">
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Connectez-vous
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdown">
                        <li><a class="dropdown-item" href="connexion.php">Connexion</a></li>
                        <li><a class="dropdown-item" href="inscription.php">Inscription</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <div class="vr"></div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="themeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thème
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="themeDropdown">
                        <li><a class="dropdown-item" href="#" onclick="setLightTheme()">Clair</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setDarkTheme()">Sombre</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

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
        // Connexion bsd
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "tutoria";

        $connex = new mysqli($servername, $username, $password, $dbname);

        if ($connex->connect_error) {
            die("Échec de la connexion : " . $connex->connect_error);
        }

        // recup formateur
        $sqlReq = "SELECT username, bio, pdp FROM users WHERE role='formateur'";
        $result = $connex->query($sqlReq);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4">';
                echo '<div class="card mb-4">';
                echo '<img src="images/' . htmlspecialchars($row["pdp"]) . '" alt="Photo de profil" class="card-img-top">';
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
<script>
    function setLightTheme() {
        document.body.className = 'light-theme';
    }

    function setDarkTheme() {
        document.body.className = 'dark-theme';
    }
</script>
</body>
</html>
