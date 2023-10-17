<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sorties</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <style>
        /* Utilisez du CSS pour afficher les éléments côte à côte */
        .container {
            display: flex;
            justify-content: space-between;
        }

        .sorties-container {
            flex: 1;
            margin-right: 20px; /* Marge à droite pour séparer les éléments */
        }

        /* Ajustez la taille du formulaire */
        .participation-form {
            max-width: 200px;
        }
    </style>
</head>
<body>

<?php
include('../model/config.php');

// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION['utilisateur_connecte']) || !$_SESSION['utilisateur_connecte']) {
    header('Location: connexion.php');
    exit();
}

include('header.php');

function getColorForSortie($sortieId, $utilisateurId, $conn) {
    // Vérifiez si le couple id utilisateur/id sortie existe dans la table participation
    $sql_verifier_participation = "SELECT compense FROM participations WHERE utilisateur_id = ? AND sortie_id = ?";
    $stmtVerifParticipation = $conn->prepare($sql_verifier_participation);

    if ($stmtVerifParticipation) {
        $stmtVerifParticipation->bind_param("ii", $utilisateurId, $sortieId);
        $stmtVerifParticipation->execute();
        $stmtVerifParticipation->store_result();

        if ($stmtVerifParticipation->num_rows > 0) {
            $stmtVerifParticipation->bind_result($compense);
            $stmtVerifParticipation->fetch();

            // Déterminez la couleur en fonction de l'attribut "compense"
            if ($compense == 0) {
                return 'green'; // Vert si compense est à 0
            } else {
                return 'orange'; // Orange si compense est à 1
            }
        }
    }

    return 'red'; // Pas de changement par défaut
}

?>

<div class="container">
    <div class="sorties-container">
        <h2>Voici la liste des sorties : </h2>

        <?php
        $sql_select_sorties = "SELECT * FROM sorties ORDER BY date DESC";
        $result_sorties = $conn->query($sql_select_sorties);

        if ($result_sorties->num_rows > 0) {
            echo '<table>';
            echo '<tbody>';

            while ($row = $result_sorties->fetch_assoc()) {
                // Obtenez la couleur en fonction de l'utilisateur et de la sortie
                $color = getColorForSortie($row['id'], $_SESSION['utilisateur_id'], $conn);

                // Appliquez la couleur à la ligne
                echo '<tr class="tab-sorties" style="background-color:' . $color . ';">';
                echo '<td>' . htmlspecialchars($row['nom'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($row['km'], ENT_QUOTES, 'UTF-8') . 'km' . '</td>';
                echo '<td>' . htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td style="width:20%"><a href="image_detail.php?image=' . $row['image'] . '"><img style="width:100%" src="' . $row['image'] . '" alt="Parcours"></a></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo 'Aucune sortie trouvée.';
        }
        ?>
    </div>

    <div class="participation-container">
        <h3>Participer à une sortie</h3>
        <form class="participation-form" action="../controler/traitement_participation.php" method="post">
            <label for="code">Code de la sortie :</label>
            <input type="text" name="code" required>
            <input class=big-button type="submit" value="Participer">
            <?php
            if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
                echo '<p class="error-message">' . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . '</p>';
                unset($_SESSION['message']); // Supprimez la variable de session après l'affichage
            }
            ?>
        </form>
    </div>
</div>

</body>
</html>
