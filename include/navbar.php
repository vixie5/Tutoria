<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="assets/images/tutoria.png" alt="Logo" width="155" height="50" class="d-inline-block align-text-top">
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdown">
                            <li><a class="dropdown-item" href="deconnexion.php">Déconnexion</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Connectez-vous
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdown">
                            <li><a class="dropdown-item" href="connexion.php">Connexion</a></li>
                            <li><a class="dropdown-item" href="inscription.php">Inscription</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <div class="vr"></div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="themeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thème
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="themeDropdown">
                        <li><a class="dropdown-item" href="#" onclick="setLightTheme(); console.log('Clair')">Clair</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setDarkTheme(); console.log('Sombre')">Sombre</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
