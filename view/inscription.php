<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h1 class="header">Inscription</h1>

    <div class="container">
        <!-- Formulaire d'inscription -->
        <form action="../controler/traitement_inscription.php" method="post">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" required><br>
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" required><br>
            <label for="email">Email :</label>
            <input type="email" name="email" required><br>
            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" required><br>
            <input class="big-button" type="submit" value="S'inscrire">
        </form>
    </div>

    <!-- Lien vers la page de connexion -->
    <div class="container">
        <p>Si vous avez déjà un compte, <a href="connexion.php">connectez-vous ici</a>.</p>
    </div>

    <!-- Lien vers la page d'accueil -->
    <div class="container">
        <a href="../index.php">Retour à l'accueil</a>
    </div>
</body>
</html>
