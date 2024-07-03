<?php
include 'include/bsd.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sqlReq = "SELECT username, bio, pdp, description_complete FROM users WHERE id = ? AND role = 'formateur'";
$stmt = $connex->prepare($sqlReq);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$formateur = $result->fetch_assoc();

if (!$formateur) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Formateur - <?php echo htmlspecialchars($formateur['username']); ?></title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .formateur-details {
            background-color: var(--card-bg-color);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-bottom: 40px;
        }
        .formateur-image-container {
            position: relative;
            width: 220px;
            height: 220px;
            margin: 0 auto 20px;
        }
        .formateur-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid var(--text-color);
        }
        .formateur-username {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 20px;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #007bff;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50%;
            height: 3px;
            background-color: #007bff;
            border-radius: 2px;
        }
        .bio-section {
            background-color: rgba(var(--bg-color-rgb), 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .bio-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .bio-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-color);
        }
        .tinymce-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-color);
        }
        .tinymce-content img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="light-theme">

<?php include 'include/navbar.php'; ?>

<div class="container mt-5">
    <a href="javascript:history.back()" class="btn btn-outline-primary mb-4">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    
    <div class="formateur-details">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="formateur-image-container">
                    <img src="assets/images/upload/<?php echo htmlspecialchars($formateur['pdp']); ?>" alt="Photo de profil" class="formateur-image">
                </div>
                <h1 class="formateur-username"><?php echo htmlspecialchars($formateur['username']); ?></h1>
            </div>
            <div class="col-md-8">
                <div class="bio-section">
                    <h3 class="section-title">Biographie</h3>
                    <p class="bio-content"><?php echo nl2br(htmlspecialchars($formateur['bio'])); ?></p>
                </div>
                <?php if (!empty($formateur['description_complete'])): ?>
                <div class="bio-section mt-4">
                    <h3 class="section-title">Description complète</h3>
                    <div class="tinymce-content">
                        <?php echo $formateur['description_complete']; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/theme.js"></script>

</body>
</html>