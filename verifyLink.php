<?php
include 'include/bsd.php';

$lienId = $_GET['lien'];

$sql = "SELECT * FROM lien_temporaire WHERE id = '$lienId'";
$result = $connex->query($sql);
$row = $result->fetch_assoc();

if ($row) {
    $dateExpiration = $row['date_expiration'];
    $utilisationRestante = $row['utilisation_restante'];

    if ($utilisationRestante > 0 && strtotime($dateExpiration) >= time()) {
        // Le lien est valide
        // Réduire le nombre d'utilisations restantes
        $utilisationRestante--;
        if ($utilisationRestante == 0) {
            // Supprimer le lien s'il n'a plus d'utilisations restantes
            $sql = "DELETE FROM lien_temporaire WHERE id = '$lienId'";
        } else {
            // Mettre à jour le nombre d'utilisations restantes
            $sql = "UPDATE lien_temporaire SET utilisation_restante = '$utilisationRestante' WHERE id = '$lienId'";
        }
        $connex->query($sql);
        // Rediriger vers la page de chapitre
        header("Location: chapitre.php?id=" . $row['chapitre_id']);
    } else {
        // Le lien a expiré ou a atteint sa limite d'utilisations
        echo "Ce lien n'est plus valide.";
    }
} else {
    // Le lien n'existe pas
    echo "Lien invalide.";
}

$connex->close();
?>
