<?php
session_start();
include 'include/bsd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $email = $_POST['email'];

    // Vérifiez si l'email existe et si le mot de passe actuel est correct
    $stmt = $connex->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($currentPassword, $user['password'])) {
            // Le mot de passe actuel est correct, mettez à jour avec le nouveau
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $connex->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $hashedNewPassword, $user['id']);
            $updateStmt->execute();

            echo "Votre mot de passe a été mis à jour avec succès.";
        } else {
            echo "Le mot de passe actuel est incorrect.";
        }
    } else {
        echo "Aucun compte trouvé avec cette adresse e-mail.";
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
    <title>Modifier le mot de passe</title>
    <link rel="icon" type="image/png" href="assets/images/logoTutoria.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Modifier votre mot de passe</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="currentPassword" class="form-label">Mot de passe actuel</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>