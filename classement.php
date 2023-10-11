<!DOCTYPE html>
<html>
<head>
    <title>Classement</title>
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

    // Exécutez une requête SQL pour obtenir les données de la table "classement"
    $sql_classement = "SELECT utilisateurs.nom, utilisateurs.prenom, classement.points FROM utilisateurs JOIN classement ON utilisateurs.id = classement.utilisateur_id ORDER BY classement.points DESC";
    $result_classement = $conn->query($sql_classement);
    ?>
    <?php include('header.php'); ?>
    <div class="container centered-heading">
        <h1>Classement des meilleurs coureurs !</h1>
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th class="texte-visible">Place</th>
                    <th class="texte-visible">Nom</th>
                    <th class="texte-visible">Prénom</th>
                    <th class="texte-visible">Points</th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                include('config.php');
                $sql_classement = "SELECT utilisateurs.nom, utilisateurs.prenom, classement.points FROM utilisateurs JOIN classement ON utilisateurs.id = classement.utilisateur_id ORDER BY classement.points DESC";
                $result_classement = $conn->query($sql_classement);
                $classement = 1; // Initialisation du classement à 1
                while ($row = $result_classement->fetch_assoc()) {
                    $ligne_class = ''; // Classe CSS par défaut (aucune couleur)
        
                    if ($classement == 1) {
                        $ligne_class = 'ligne-or';
                    } elseif ($classement == 2) {
                        $ligne_class = 'ligne-argent';
                    } elseif ($classement == 3) {
                        $ligne_class = 'ligne-bronze';
                    }
        
                    echo "<tr class='$ligne_class'>"; // Appliquer la classe CSS
                    
                    echo "<td class='texte-visible'>" . $classement . "</td>"; // Affiche le classement
                    echo "<td class='texte-visible'>" . $row['nom'] . "</td>";
                    echo "<td class='texte-visible'>" . $row['prenom'] . "</td>";
                    echo "<td class='texte-visible'>" . $row['points'] . "</td>";
                    echo "</tr>";
                    $classement++; // Incrémente le classement
                }
                ?>
            </tbody>
            </table>
        </table>
    </div>
    <div class="container2">
    <h3>Le classement est incrémenté de 1 pour chaque sortie réalisée avec le groupe </h3>
    <h3>Si vous ratez une sortie, le compteur retombe à 0 ! </h3>

    </div>


</body>
</html>
