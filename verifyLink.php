<?php
include 'include/bsd.php';

if (!isset($_GET['chapitreId']) || !isset($_GET['token'])) {
    echo "Lien invalide.";
    exit();
}

$chapitreId = $_GET['chapitreId'];
$token = $_GET['token'];

$sql = "SELECT * FROM lien_temporaire WHERE chapitre_id = ? AND lien LIKE ? AND date_expiration >= CURDATE()";
$stmt = $connex->prepare($sql);
$tokenPattern = "%$token%";
$stmt->bind_param("is", $chapitreId, $tokenPattern);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: clientFormation.php?chapitreId=$chapitreId&token=$token");
    exit();
} else {
    echo "Ce lien n'est plus valide.";
}

$connex->close();
?>