<?php
session_start();
include('config.php');

if ($_SESSION['utilisateur_connecte']) {
    // Vous pouvez ajouter des vérifications supplémentaires ici si nécessaire

    $utilisateurId = $_SESSION['utilisateur_id'];

    // Vérifiez d'abord si l'utilisateur est déjà dans la table "classement"
    $sql_verif_utilisateur = "SELECT * FROM classement WHERE utilisateur_id = $utilisateurId";
    $result_verif_utilisateur = $conn->query($sql_verif_utilisateur);

    if ($result_verif_utilisateur->num_rows == 0) {
        // Si l'utilisateur n'est pas dans la table "classement", ajoutez-le
        $sql_ajout_utilisateur = "INSERT INTO classement (utilisateur_id, points) VALUES ($utilisateurId, 1)";
        if ($conn->query($sql_ajout_utilisateur) === TRUE) {
            // L'utilisateur a été ajouté à la table "classement"
        } else {
            echo "Erreur lors de l'ajout de l'utilisateur au classement : " . $conn->error;
        }
    } else {
        // Si l'utilisateur est déjà dans la table "classement", mettez à jour ses points
        $sql = "UPDATE classement SET points = points + 1 WHERE utilisateur_id = $utilisateurId";
        if ($conn->query($sql) === TRUE) {
            // Points ajoutés avec succès
        } else {
            echo "Erreur lors de l'ajout des points : " . $conn->error;
        }
    }

    // Redirigez l'utilisateur vers la page "classement.php" après l'ajout des points
    header('Location: classement.php');
} else {
    echo "Erreur : Utilisateur non connecté.";
}

$conn->close();
?>
