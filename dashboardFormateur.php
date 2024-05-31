<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutoria";

$connex = new mysqli($servername, $username, $password, $dbname);

if ($connex->connect_error) {
    die("Échec de la connexion : " . $connex->connect_error);
}

$formateurUsername = $_SESSION['username'];

$sqlReq = "SELECT * FROM chapitres WHERE formateur_id = (SELECT id FROM users WHERE username='$formateurUsername')";
$resultat = $connex->query($sqlReq);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formateur</title>
    <link rel="icon" type="image/png" href="images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px var(--card-shadow);
            border: 1px solid var(--card-border-color);
            background-color: var(--card-bg-color);
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
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
        .dark-theme .video-title {
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
            margin-top: 30px;
        }
        .card-section {
            margin-bottom: 20px;
        }
        .icon-action {
            margin-left: 10px;
            cursor: pointer;
            color: gray;
            font-size: 0.8em;
        }
        .icon-action:hover {
            color: var(--link-hover-color);
        }
        .btn-yellow {
            background-color: #ffd700;
            color: #000;
            border: none;
        }
        .btn-yellow:hover {
            background-color: #e6be00;
            color: #000;
        }
        .icon-action.text-warning {
            color: gray !important;
        }
    </style>
</head>
<body class="light-theme" id="theme-body">

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="images/tutoria.png" alt="Logo" width="155" height="50" class="d-inline-block align-text-top">
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdown">
                        <li><a class="dropdown-item" href="deconnexion.php">Déconnexion</a></li>
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
    <h2 class="mb-4">Vos Formations</h2>
    <?php
    if ($resultat->num_rows > 0) {
        while ($row = $resultat->fetch_assoc()) {
            $chapitreId = $row['id'];
            echo "<div class='card'>
                    <div class='card-body'>
                        <h5 class='card-title d-flex justify-content-between'>
                            {$row['titre_chapitre']}
                            <span>
                                <a href='modifierChapitre.php?id={$row['id']}' class='text-primary icon-action'><i class='fas fa-pen'></i></a>
                                <a href='supprimerChapitre.php?id={$row['id']}' class='text-secondary icon-action'><i class='fas fa-trash-alt'></i></a>
                            </span>
                        </h5>";

            // recup video chap
            $reqVideo = "SELECT * FROM videos WHERE chapitre_id='$chapitreId'";
            $video = $connex->query($reqVideo);

            if ($video->num_rows > 0) {
                echo "<div class='card-section'>";
                while ($video_row = $video->fetch_assoc()) {
                    echo "<div class='d-flex justify-content-between align-items-center mb-2'>
                            <div>
                                <h6 class='video-title mb-0'>{$video_row['titre_video']}</h6>
                                <a href='{$video_row['url_video']}' target='_blank'>{$video_row['url_video']}</a>
                            </div>
                            <span>
                                <a href='modifierVideo.php?id={$video_row['id']}' class='text-primary icon-action'><i class='fas fa-pen'></i></a>
                                <a href='supprimerVideo.php?id={$video_row['id']}' class='text-secondary icon-action'><i class='fas fa-trash-alt'></i></a>
                            </span>
                          </div>";
                }
                echo "</div>";
            }

            echo "<div class='d-flex justify-content-end mt-3'>
                    <a href='ajouterVideo.php?chapitre_id={$row['id']}' class='btn btn-primary btn-sm'>Ajouter Vidéo</a>
                  </div>
                </div>
              </div>";
        }
    } else {
        echo "<p>Aucun chapitre trouvé.</p>";
    }
    ?>
    <div class="d-flex justify-content-end mt-4">
        <a href="ajouterFormation.php" class="btn btn-yellow me-2">Ajouter Formation</a>
        <a href="ajouterClient.php" class="btn btn-yellow">Ajouter Client</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function setLightTheme() {
        document.body.className = 'light-theme';
        document.cookie = "theme=light; path=/";
    }

    function setDarkTheme() {
        document.body.className = 'dark-theme';
        document.cookie = "theme=dark; path=/";
    }

    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) {
            return match[2];
        }
        return null;
    }

    window.onload = function() {
        let theme = getCookie('theme');
        if (theme === 'dark') {
            setDarkTheme();
        } else {
            setLightTheme();
        }
    };
</script>
</body>
</html>

<?php
$connex->close();
?>
