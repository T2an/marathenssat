<?php
include('../model/config.php');

$nom_utilisateur = $prenom_utilisateur = '';
$administration_link = ''; // Initialisez la variable pour éviter les erreurs

if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']) {
    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Utilisez des requêtes préparées pour éviter les injections SQL
    $sql_nom_prenom = "SELECT nom, prenom FROM utilisateurs WHERE id = ?";
    $stmt_nom_prenom = $conn->prepare($sql_nom_prenom);

    if ($stmt_nom_prenom) {
        $stmt_nom_prenom->bind_param("i", $utilisateur_id);
        $stmt_nom_prenom->execute();
        $stmt_nom_prenom->store_result();

        if ($stmt_nom_prenom->num_rows == 1) {
            $stmt_nom_prenom->bind_result($nom_utilisateur, $prenom_utilisateur);
            $stmt_nom_prenom->fetch();
        }

        // Fermez l'instruction préparée
        $stmt_nom_prenom->close();
    }

    // Utilisez également une requête préparée pour vérifier si l'utilisateur est administrateur
    $sql_verif_admin = "SELECT id FROM administrateurs WHERE utilisateur_id = ?";
    $stmt_verif_admin = $conn->prepare($sql_verif_admin);

    if ($stmt_verif_admin) {
        $stmt_verif_admin->bind_param("i", $utilisateur_id);
        $stmt_verif_admin->execute();
        $stmt_verif_admin->store_result();

        if ($stmt_verif_admin->num_rows > 0) {
            $administration_link = '<div class="nav-box"><a href="administration.php">Administration</a></div>';
        }

        // Fermez l'instruction préparée
        $stmt_verif_admin->close();
    }
}
?>

<div class="header">
    <div class="nav-box">
        <a href="profil.php"><?php echo htmlspecialchars($nom_utilisateur) . " " . htmlspecialchars($prenom_utilisateur); ?></a>
    </div>
    <div class="nav-box">
        <a href="classement.php">Classement</a>
    </div>
    <div class="nav-box">
        <a href="sorties.php">Sorties</a>
    </div>

    <?php echo $administration_link ?? ''; // Affichez l'option "Administration" si elle existe ?>
    <div class="nav-box">
        <a href="../controler/deconnexion.php">Se déconnecter</a>
    </div>
</div>
