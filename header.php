<?php
session_start();
include('config.php');

$nom_utilisateur = $prenom_utilisateur = '';

if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte']) {
    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Récupérez le nom et le prénom de l'utilisateur connecté à partir de la base de données
    $sql_nom_prenom = "SELECT nom, prenom FROM utilisateurs WHERE id = $utilisateur_id";
    $result_nom_prenom = $conn->query($sql_nom_prenom);

    if ($result_nom_prenom->num_rows == 1) {
        $row_nom_prenom = $result_nom_prenom->fetch_assoc();
        $nom_utilisateur = $row_nom_prenom['nom'];
        $prenom_utilisateur = $row_nom_prenom['prenom'];
    }

    // Vérifiez si l'utilisateur est administrateur
    $sql_verif_admin = "SELECT id FROM administrateurs WHERE utilisateur_id = $utilisateur_id";
    $result_verif_admin = $conn->query($sql_verif_admin);

    if ($result_verif_admin->num_rows > 0) {
        // Si l'utilisateur est administrateur, affichez l'option "Administration"
        $administration_link = '<div class="nav-box"><a href="administration.php">Administration</a></div>';
    }
}
?>

<div class="header">
    <div class="nav-box">
        <a href="profil.php"><?php echo $nom_utilisateur . " " . $prenom_utilisateur; ?></a>
    </div>
    <div class="nav-box">
        <a href="classement.php">Classement</a>
    </div>

    <div class="nav-box">
        <a href="sorties.php">Sorties</a>
    </div>

    <?php echo $administration_link ?? ''; // Affichez l'option "Administration" si elle existe ?>
    <div class="nav-box">
        <a href="deconnexion.php">Se déconnecter</a>
    </div>
</div>
