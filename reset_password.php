<?php
include 'include/bsd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Vérifiez si l'email existe dans la base de données
    $stmt = $connex->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Générez un nouveau mot de passe aléatoire
        $newPassword = bin2hex(random_bytes(8));
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Mettez à jour le mot de passe dans la base de données
        $updateStmt = $connex->prepare("UPDATE users SET password = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $email);
        $updateStmt->execute();
        
        // Envoyez l'e-mail
        $to = $email;
        $subject = "Réinitialisation de votre mot de passe";
        $message = "Votre nouveau mot de passe est : " . $newPassword . "\n\n";
        $message .= "Veuillez cliquer sur ce lien pour modifier votre mot de passe : https://tutoria.sendix.fr/nouveauMdp.php";
        $headers = "From: noreply@votredomaine.com";
        
        if (mail($to, $subject, $message, $headers)) {
            echo "Un e-mail contenant votre nouveau mot de passe a été envoyé.";
        } else {
            echo "Erreur lors de l'envoi de l'e-mail.";
        }
    } else {
        echo "Aucun compte trouvé avec cette adresse e-mail.";
    }
    
    $stmt->close();
    $connex->close();
}