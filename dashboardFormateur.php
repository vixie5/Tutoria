<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'formateur') {
    header('Location: connexion.php');
    exit();
}

include 'include/bsd.php';

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
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .modern-section h3 {
            color: var(--text-color);
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        .modern-section h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: var(--link-color);
        }
        .modern-card {
            background-color: var(--bg-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .modern-card h5 {
            color: var(--text-color);
            font-weight: normal;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .modern-card p {
            color: var(--text-color);
            opacity: 0.8;
            font-size: 0.8rem;
        }
        .modern-btn {
            background-color: #ffd700;
            color: #000;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 0.8rem;
        }
        .modern-btn:hover {
            background-color: #ffcc00;
        }

        
        .link-item {
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .link-title {
        font-weight: bold;
    }
    .link-expiration {
        font-style: italic;
    }
    </style>
    <script>
    function openShareModal(chapitreId) {
        document.getElementById('shareModal').style.display = 'block';
        document.getElementById('chapitreId').value = chapitreId;
    }

    function generateLink(event) {
        event.preventDefault();
        const chapitreId = document.getElementById('chapitreId').value;
        const expirationDate = document.getElementById('expirationDate').value;

        fetch('generationLien.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `chapitreId=${chapitreId}&expirationDate=${expirationDate}`
        })
        .then(response => response.text())
        .then(data => {
            const linkContainer = document.getElementById('generatedLink');
            const link = data.match(/<a href='(.+?)'>/)[1];
            linkContainer.innerHTML = `
                <div class="d-flex align-items-center mt-3">
                    <input type="text" value="${link}" id="generatedLinkInput" class="form-control me-2" readonly>
                    <button onclick="copyLink()" class="btn btn-primary">Copier</button>
                </div>
            `;
        });
    }

    function copyLink() {
        const linkInput = document.getElementById('generatedLinkInput');
        linkInput.select();
        document.execCommand('copy');
        alert('Lien copié dans le presse-papiers!');
    }

    function confirmDeletion(type, id) {
        let message;
        let url;
        if (type === 'chapitre') {
            message = 'Êtes-vous sûr de vouloir supprimer ce chapitre ?';
            url = `supprimerChapitre.php?id=${id}`;
        } else if (type === 'video') {
            message = 'Êtes-vous sûr de vouloir supprimer cette vidéo ?';
            url = `supprimerVideo.php?id=${id}`;
        } else if (type === 'lien') {
            message = 'Êtes-vous sûr de vouloir supprimer ce lien temporaire ?';
            url = `supprimerLien.php?id=${id}`;
        } else if (type === 'mail') {
            message = 'Êtes-vous sûr de vouloir supprimer cet accès permanent ?';
            url = `supprimerMail.php?email=${id}`;
        } else {
            message = 'Êtes-vous sûr de vouloir supprimer cet élément ?';
            url = '#';
        }
        
        if (confirm(message)) {
            window.location.href = url;
        }
    }
    </script>
</head>

<body class="light-theme" id="dashboardFormateur">

<?php include 'include/navbar.php'; ?>

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
                                <a href='javascript:void(0);' onclick='confirmDeletion(\"chapitre\", {$row['id']})' class='text-secondary icon-action'><i class='fas fa-trash-alt'></i></a>
                                <a href='javascript:void(0);' onclick='openShareModal({$row['id']})' class='text-success icon-action'><i class='fas fa-share-alt'></i></a>
                            </span>
                        </h5>
                        <p class='card-text d-flex justify-content-between align-items-center'>
                            {$row['description_chapitre']}
                            <span>
                                <a href='modifierDescription.php?id={$row['id']}' class='text-primary icon-action'><i class='fas fa-pen'></i></a>
                            </span>
                        </p>";

            // Récupérer les vidéos du chapitre
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
                                <a href='javascript:void(0);' onclick='confirmDeletion(\"video\", {$video_row['id']})' class='text-secondary icon-action'><i class='fas fa-trash-alt'></i></a>
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
        <a href="ajouterFormation.php" class="btn btn-warning me-2">Ajouter Formation</a>
        <a href="ajouterClient.php" class="btn btn-warning">Ajouter Client</a>
    </div>
</div>

<div class="container mt-5">
    <div class="modern-section">
        <h3>Liens temporaires</h3>
        <div class="d-flex flex-wrap">
            <?php
            $sqlLiens = "SELECT lt.*, ch.titre_chapitre
                         FROM lien_temporaire lt
                         JOIN chapitres ch ON lt.chapitre_id = ch.id
                         WHERE lt.formateur_id = (SELECT id FROM users WHERE username='$formateurUsername')";
            $resultatLiens = $connex->query($sqlLiens);
            
            if ($resultatLiens->num_rows > 0) {
                while ($lien = $resultatLiens->fetch_assoc()) {
                    echo "<div class='d-flex align-items-center me-3 mb-2 link-item'>
                            <h5 class='mb-0 link-title'>{$lien['titre_chapitre']} :</h5>
                            <div class='d-flex flex-column ms-2'>
                                <span>Lien : <a href='{$lien['lien']}' target='_blank'>{$lien['lien']}</a></span>
                                <span class='link-expiration'>Expiration : {$lien['date_expiration']}</span>
                            </div>
                            <a href='javascript:void(0);' onclick='confirmDeletion(\"lien\", {$lien['id']})' class='modern-btn ms-2'><i class='fas fa-trash-alt'></i></a>
                          </div>";
                }
            } else {
                echo "<p>Aucun lien temporaire trouvé.</p>";
            }
            ?>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="modern-section">
        <h3>Accès permanents</h3>
        <div class="row">
            <?php
            $sqlClients = "SELECT u.email FROM users u 
                           JOIN formations_clients fc ON u.id = fc.client_id 
                           WHERE fc.formateur_id = (SELECT id FROM users WHERE username='$formateurUsername')";
            $resultatClients = $connex->query($sqlClients);
            
            if ($resultatClients->num_rows > 0) {
                while ($client = $resultatClients->fetch_assoc()) {
                    echo "<div class='col-md-4 mb-3'>
                            <div class='modern-card'>
                                <h5>Email : {$client['email']}</h5>
                                <a href='javascript:void(0);' onclick='confirmDeletion(\"mail\", \"{$client['email']}\")' class='modern-btn'><i class='fas fa-trash-alt'></i> Supprimer</a>
                            </div>
                        </div>";
                }
            } else {
                echo "<p>Aucun accès permanent trouvé.</p>";
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/theme.js"></script>

<div id="shareModal" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Générer un lien temporaire</h5>
                <button type="button" class="btn-close" onclick="document.getElementById('shareModal').style.display='none';"></button>
            </div>
            <div class="modal-body">
                <form id="shareForm" onsubmit="generateLink(event)">
                    <input type="hidden" id="chapitreId" name="chapitreId">
                    <div class="mb-3">
                        <label for="expirationDate" class="form-label">Date d'expiration</label>
                        <input type="date" id="expirationDate" name="expirationDate" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Générer le lien</button>
                </form>
                <div id="generatedLink" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
$connex->close();
?>
