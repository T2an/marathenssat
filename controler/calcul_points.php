<?php
session_start();
include('../model/config.php');

// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION['utilisateur_connecte']) || !$_SESSION['utilisateur_connecte']) {
    header('Location: ../view/connexion.php');
    exit();
}

// Récupérez l'ID de l'utilisateur connecté à partir de la session
$utilisateur_id = $_SESSION['utilisateur_id'];

// Sélectionnez tous les utilisateurs
$sql_select_id_user = "SELECT id FROM utilisateurs";
$result_user = $conn->query($sql_select_id_user);

while ($row2 = $result_user->fetch_assoc()) {
    $idu = $row2['id'];
    $points = 0;
    $sorties_realisees = 0;

    // Sélectionnez toutes les sorties de la table "sorties" triées par date décroissante
    $sql_select_sorties = "SELECT id FROM sorties ORDER BY date DESC";
    $result_sorties = $conn->query($sql_select_sorties);

    while ($row = $result_sorties->fetch_assoc()) {
        $sortie_id = $row['id'];

        // Vérifiez si l'utilisateur a déjà participé à cette sortie
        $sql_verifier_participation = "SELECT compense FROM participations WHERE utilisateur_id = ? AND sortie_id = ?";
        $stmtVerifParticipation = $conn->prepare($sql_verifier_participation);

        if ($stmtVerifParticipation) {
            $stmtVerifParticipation->bind_param("ii", $idu, $sortie_id);
            $stmtVerifParticipation->execute();
            $stmtVerifParticipation->store_result();

            if ($stmtVerifParticipation->num_rows != 0) {
                $stmtVerifParticipation->bind_result($compense);

                while ($stmtVerifParticipation->fetch()) {
                    // Vérifiez la valeur de "compense" et ajoutez un point si elle est égale à 0
                    if ($compense == 0) {
                        $points++;
                    }
                }
                $sorties_realisees++;
            } else {
                break; // Sortir de la boucle si l'utilisateur a déjà participé à une sortie
            }
        } else {
            echo "Erreur lors de la préparation de la requête de vérification de participation : " . $conn->error;
        }
    }

    // Mettez à jour le score de l'utilisateur dans la table "classement"
    $sql_maj_points = "UPDATE classement SET points = ? WHERE utilisateur_id = ?";
    $stmtMajPoints = $conn->prepare($sql_maj_points);

    if ($stmtMajPoints) {
        $stmtMajPoints->bind_param("ii", $points, $idu);
        $stmtMajPoints->execute();
    } else {
        echo "Erreur lors de la préparation de la requête de mise à jour des points : " . $conn->error;
    }
}

// Redirigez l'utilisateur vers la page de classement
header('Location: ../view/sorties.php');
exit();
?>
