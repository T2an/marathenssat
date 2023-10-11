<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<?php
session_start();

// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION['utilisateur_connecte']) || !$_SESSION['utilisateur_connecte']) {
    header('Location: connexion.php');
    exit();
}

include('config.php');

// Récupérez le nom et le prénom de l'utilisateur connecté à partir de la base de données
$utilisateur_id = $_SESSION['utilisateur_id'];
$sql_nom_prenom = "SELECT nom, prenom, email FROM utilisateurs WHERE id = $utilisateur_id";
$result_nom_prenom = $conn->query($sql_nom_prenom);

if ($result_nom_prenom->num_rows == 1) {
    $row_nom_prenom = $result_nom_prenom->fetch_assoc();
    $nom_utilisateur = $row_nom_prenom['nom'];
    $prenom_utilisateur = $row_nom_prenom['prenom'];
    $email_utilisateur = $row_nom_prenom['email'];

}
?>

<?php include('header.php'); ?>

<div class="container2">

<h2>Profil de l'utilisateur</h2>
</div>
<div class="container2">
    <p>Nom : <?php echo $nom_utilisateur; ?></p>
    <p>Prénom : <?php echo $prenom_utilisateur; ?></p>
    <p>email : <?php echo $email_utilisateur; ?></p>

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
