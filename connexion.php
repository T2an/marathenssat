<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<head>
    <title>Connexion</title>
</head>
<link rel="stylesheet" type="text/css" href="styles.css">

<body>
    <h1 class=header>Connexion</h1>
    <div class="container">

    <!-- Affichez le message d'erreur s'il existe -->
    <?php
    session_start();
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        echo '<p class="error-message">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']); // Supprimez la variable de session après l'affichage
    }
    ?>

    <form action="traitement_connexion.php" method="post">
        <label for="email">Email :</label>
        <input type="email" name="email" required><br>
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" name="mot_de_passe" required><br>
        <input class=big-button type="submit" value="Se connecter">

    </form>
</div>
<div class="container">

    <!-- Lien vers la page d'inscription -->
    <p>Si vous n'avez pas de compte, <a href="inscription.php">inscrivez-vous ici</a>.</p>
    
    <!-- Lien vers la page d'accueil -->
    </div>
    <div class="container">

    <a href="index.php">Retour à l'accueil</a>
    </div>

</body>
</html>
