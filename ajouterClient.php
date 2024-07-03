<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'formateur') {
    header('Location: login.php');
    exit();
}

$popup = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'include/bsd.php';

    $formateurId = $_SESSION['user_id'];
    $clientEmail = $_POST['client_email'];

    // Utiliser une requête préparée pour éviter les injections SQL
    $stmt = $connex->prepare("SELECT id FROM users WHERE email=? AND role='client'");
    $stmt->bind_param("s", $clientEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        $clientId = $client['id'];

        // Ajouter la formation pour le client en utilisant une requête préparée
        $stmt = $connex->prepare("INSERT INTO formations_clients (formateur_id, client_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $formateurId, $clientId);

        if ($stmt->execute() === TRUE) {
            // Envoyer l'e-mail au client
            $to = $clientEmail;
            $subject = "Bienvenue sur Tutoria";
            $message = "Bienvenue sur Tutoria ! Vous pouvez accéder à vos formations en cliquant sur ce lien : https://tutoria.sendix.fr/dashboardClient.php";
            $headers = "From: noreplay@tutoria.sendix.fr\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            if (mail($to, $subject, $message, $headers)) {
                $popup = '<div class="alert alert-success" role="alert">Le client a été ajouté avec succès et un e-mail lui a été envoyé.</div>';
            } else {
                $popup = '<div class="alert alert-warning" role="alert">Le client a été ajouté, mais l\'envoi de l\'e-mail a échoué.</div>';
            }
        } else {
            $popup = '<div class="alert alert-danger" role="alert">Erreur lors de l\'ajout du client : ' . $stmt->error . '</div>';
        }
    } else {
        $popup = '<div class="alert alert-danger" role="alert">Erreur : aucun client trouvé avec cet email.</div>';
    }

    $stmt->close();
    $connex->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Client</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="light-theme" id="ajoutClient">

<!-- navbar avec un include -->
<?php include 'include/navbar.php'; ?>

<div class="container">
    <?php
    if (!empty($popup)) {
        echo $popup;
    }
    ?>
    <h2>Ajouter Client</h2>
    <form action="ajouterClient.php" method="POST">
        <div class="mb-3">
            <label for="client_email" class="form-label">Email du Client</label>
            <input type="email" class="form-control" id="client_email" name="client_email" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter Client</button>
        <a href="dashboardFormateur.php" class="btn btn-warning">Retour</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/theme.js"></script>

</body>
</html>