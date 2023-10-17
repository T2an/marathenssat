<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>

<?php

// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION['utilisateur_connecte']) || !$_SESSION['utilisateur_connecte']) {
    header('Location: connexion.php');
    exit();
}

include('../model/config.php');

// Récupérez le nom et le prénom de l'utilisateur connecté à partir de la base de données en utilisant une requête préparée
$utilisateur_id = $_SESSION['utilisateur_id'];
$sql_nom_prenom = "SELECT nom, prenom, email FROM utilisateurs WHERE id = ?";
$stmt = $conn->prepare($sql_nom_prenom);

if ($stmt) {
    $stmt->bind_param("i", $utilisateur_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($nom_utilisateur, $prenom_utilisateur, $email_utilisateur);
        $stmt->fetch();
    } else {
        // L'utilisateur n'a pas été trouvé, vous pouvez gérer cette situation
    }
    // Fermez l'instruction préparée
    $stmt->close();
}
?>
<?php include('header.php'); ?>

<div class="container2">
    <h2>Profil de l'utilisateur</h2>
</div>
<div class="container2">
    <p>Nom : <?php echo htmlspecialchars($nom_utilisateur, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Prénom : <?php echo htmlspecialchars($prenom_utilisateur, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Email : <?php echo htmlspecialchars($email_utilisateur, ENT_QUOTES, 'UTF-8'); ?></p>
</div>
<div class="container2">
    <h2>Nb kilometres total : </h2>
</div>
<div class="container2">
    <h2>Nb points : </h2>
</div>
<div class="container2">
    <h2>Nb sorties: </h2>
</div>
</body>
</html>
