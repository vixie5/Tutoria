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
    <script>
        function confirmDeletion(type, id) {
            let message = type === 'chapitre' ? "Êtes-vous sûr de vouloir supprimer ce chapitre et toutes ses vidéos ?" : "Êtes-vous sûr de vouloir supprimer cette vidéo ?";
            if (confirm(message)) {
                window.location.href = type === 'chapitre' ? 'supprimerChapitre.php?id=' + id : 'supprimerVideo.php?id=' + id;
            }
        }

        function openShareModal(chapitreId) {
            document.getElementById('shareModal').style.display = 'block';
            document.getElementById('chapitreId').value = chapitreId;
        }
    </script>
</head>

<body class="light-theme" id="dashboardFormateur">

<!-- navbar avec un include -->
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
                <form id="shareForm" action="generationLien.php" method="post">
                    <input type="hidden" id="chapitreId" name="chapitreId">
                    <div class="mb-3">
                        <label for="expirationDate" class="form-label">Date d'expiration</label>
                        <input type="date" id="expirationDate" name="expirationDate" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Générer le lien</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
$connex->close();
?>
