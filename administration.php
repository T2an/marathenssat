<!DOCTYPE html>
<html>
<head>
    <title>Administration</title>
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

?>

<?php include('header.php'); 

if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
    echo '<p class="error-message">' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']); // Supprimez la variable de session après l'affichage
}
?>
<div class="container">

<h2>Panneau d'administration</h2>
</div>

<div class="container2">
    <h3>Ajouter une sortie</h3>
    <form action="traitement_ajout_sortie.php" method="post" enctype="multipart/form-data">
        <label for="km">Kilomètres :</label>
        <input type="text" name="km" required><br>
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br>
        <label for="date">Date :</label>
        <input type="date" name="date" required><br>
        <!-- Ajoutez un champ d'envoi de fichier pour l'image -->
        <label for="image">Image :</label>
        <input type="file" name="image" accept="image/*" required><br>
        <input type="submit" class="big-button" value="Ajouter">
    </form>
</div>


</body>
</html>
