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

$sqlPayPal = "SELECT lien_paypal, bio, description_complete, pdp FROM users WHERE username = '$formateurUsername'";
$resultPayPal = $connex->query($sqlPayPal);
$userData = $resultPayPal->fetch_assoc();
$existingPayPalLink = $userData['lien_paypal'];
$existingBio = $userData['bio'];
$existingDescription = $userData['description_complete'];
$currentPhoto = $userData['pdp'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_photo'])) {
    if (isset($_FILES['pdp']) && $_FILES['pdp']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['pdp']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $newname = $formateurUsername . '.' . $filetype;
            $uploadfile = "./assets/images/upload/" . $newname;
            
            if (move_uploaded_file($_FILES['pdp']['tmp_name'], $uploadfile)) {
                $updateSql = "UPDATE users SET pdp = '$newname' WHERE username = '$formateurUsername'";
                if ($connex->query($updateSql) === TRUE) {
                    $message = "Photo de profil mise à jour avec succès.";
                    $currentPhoto = $newname;
                } else {
                    $message = "Erreur lors de la mise à jour de la photo de profil dans la base de données.";
                }
            } else {
                $message = "Erreur lors du téléchargement du fichier.";
            }
        } else {
            $message = "Type de fichier non autorisé. Veuillez choisir une image (jpg, jpeg, png, gif).";
        }
    } else {
        $message = "Erreur lors du téléchargement du fichier.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['paypal_link'])) {
        $paypalLink = $connex->real_escape_string($_POST['paypal_link']);
        $updateSql = "UPDATE users SET lien_paypal = '$paypalLink' WHERE username = '$formateurUsername'";
        if ($connex->query($updateSql) === TRUE) {
            $message = "Lien PayPal mis à jour avec succès.";
            $existingPayPalLink = $paypalLink;
        } else {
            $message = "Erreur lors de la mise à jour du lien PayPal: " . $connex->error;
        }
    } elseif (isset($_POST['bio'])) {
        $newBio = $connex->real_escape_string($_POST['bio']);
        $updateSql = "UPDATE users SET bio = '$newBio' WHERE username = '$formateurUsername'";
        if ($connex->query($updateSql) === TRUE) {
            $message = "Biographie mise à jour avec succès.";
            $existingBio = $newBio;
        } else {
            $message = "Erreur lors de la mise à jour de la biographie: " . $connex->error;
        }
    } elseif (isset($_POST['description_complete'])) {
        $newDescription = $_POST['description_complete'];
        $updateSql = "UPDATE users SET description_complete = ? WHERE username = ?";
        $stmt = $connex->prepare($updateSql);
        $stmt->bind_param("ss", $newDescription, $formateurUsername);
        if ($stmt->execute()) {
            $message = "Description complète mise à jour avec succès.";
            $existingDescription = $newDescription;
        } else {
            $message = "Erreur lors de la mise à jour de la description complète: " . $stmt->error;
        }
        $stmt->close();
    }
}
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
    <script src="assets/js/tinymce/tinymce.min.js"></script>
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
        .bio-section {
            background-color: var(--card-bg-color);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 40px;
        }
        .bio-card {
            background-color: var(--bg-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Vos Formations</h2>
        <a href="ajouterFormation.php" class="btn btn-warning">Ajouter Formation</a>
    </div>

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

<div class="container mt-5">
        <div class="modern-section">
            <h3>Vos Informations</h3>
            <div class="bio-card mt-4">
                <h5>Photo de profil</h5>
                <?php if ($currentPhoto): ?>
                    <img src="./assets/images/upload/<?php echo $currentPhoto; ?>" alt="Photo de profil" class="img-thumbnail mb-3" style="max-width: 200px;">
                <?php else: ?>
                    <p>Aucune photo de profil actuellement.</p>
                <?php endif; ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="pdp" class="form-label">Changer la photo de profil :</label>
                        <input type="file" class="form-control" id="pdp" name="pdp" accept="image/*">
                    </div>
                    <button type="submit" name="update_photo" class="btn btn-primary">Mettre à jour la photo</button>
                </form>
            </div>
            <div class="bio-card mt-4">
                <h5>Biographie actuelle</h5>
                <p><?php echo htmlspecialchars($existingBio); ?></p>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="bio" class="form-label">Modifier la biographie :</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo htmlspecialchars($existingBio); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
            <div class="bio-card mt-4">
                <h5>Description actuelle</h5>
                <?php if ($existingDescription): ?>
                    <div><?php echo $existingDescription; ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="description_complete" class="form-label"><?php echo $existingDescription ? 'Modifier' : 'Ajouter'; ?> la description complète :</label>
                        <textarea class="form-control tinymce" id="description_complete" name="description_complete" rows="5"><?php echo htmlspecialchars($existingDescription); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $existingDescription ? 'Mettre à jour' : 'Ajouter'; ?></button>
                </form>
            </div>
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
        <div class="mb-3">
            <a href="ajouterClient.php" class="btn btn-warning">Ajouter Client</a>
        </div>
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

<div class="container mt-5">
    <div class="modern-section">
        <h3>Intégration PayPal</h3>
        <div class="row">
            <div class="col-md-6">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>
            <div class="col-md-6">
                <p><strong>Cliquez ici pour créer votre bouton :</strong></p>
                <a href="https://www.paypal.com/buttons/smart" target="_blank" class="btn btn-primary mb-3">Créer un bouton PayPal</a>
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="paypal_link" class="form-label"><strong>Coller le lien de votre bouton :</strong></label>
                        <input type="text" class="form-control" id="paypal_link" name="paypal_link" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Envoyer</button>
                </form>
                <?php
                if (isset($message)) {
                    echo "<div class='alert alert-info mt-3'>$message</div>";
                }
                if ($existingPayPalLink) {
                    echo "<div class='mt-3'><strong>Lien PayPal actuel :</strong> <a href='$existingPayPalLink' target='_blank'>$existingPayPalLink</a></div>";
                }
                ?>
            </div>
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

<script>
tinymce.init({
    selector: '#description_complete',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
    ]
});
</script>

</body>
</html>

<?php
$connex->close();
?>