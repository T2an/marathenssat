<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h1 class="header">Connexion</h1>
    <div class="container">

        <!-- Affichez le message d'erreur s'il existe -->
        <?php
        session_start();
        if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
            echo '<p class="error-message">' . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . '</p>';
            unset($_SESSION['message']); // Supprimez la variable de session après l'affichage
        }
        ?>

        <form action="../controler/traitement_connexion.php" method="post">
            <label for="email">Email :</label>
            <input type="email" name="email" required><br>
            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" required><br>
            <input class="big-button" type="submit" value="Se connecter">
        </form>
    </div>
    <div class="container">

        <!-- Lien vers la page d'inscription -->
        <p>Si vous n'avez pas de compte, <a href="inscription.php">inscrivez-vous ici</a>.</p>

    </div>
    <!-- Lien vers la page d'accueil -->
    <div class="container">
        <a href="../index.php">Retour à l'accueil</a>
    </div>
</body>
</html>
