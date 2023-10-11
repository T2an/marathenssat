<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header>
        <h1>Bienvenue sur Marathenssat !</h1>
    </header>

    <div class="container">
        <h2>Classement des Marathenssatiens</h2>
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
                $sql_classement = "SELECT utilisateurs.nom, utilisateurs.prenom, classement.points 
                                  FROM utilisateurs 
                                  JOIN classement ON utilisateurs.id = classement.utilisateur_id 
                                  ORDER BY classement.points DESC";
                $result_classement = $conn->query($sql_classement);
                $classement = 1; // Initialisation du classement à 1

                while ($row = $result_classement->fetch_assoc()) {
                    $ligne_class = ''; // Classe CSS par défaut (aucune couleur)

                    // Appliquer les classes CSS en fonction de la place dans le classement
                    if ($classement == 1) {
                        $ligne_class = 'ligne-or';
                    } elseif ($classement == 2) {
                        $ligne_class = 'ligne-argent';
                    } elseif ($classement == 3) {
                        $ligne_class = 'ligne-bronze';
                    }

                    echo "<tr class='$ligne_class'>"; // Appliquer la classe CSS
                    echo "<td class='texte-visible'>$classement</td>"; // Affiche le classement
                    echo "<td class='texte-visible'>" . $row['nom'] . "</td>";
                    echo "<td class='texte-visible'>" . $row['prenom'] . "</td>";
                    echo "<td class='texte-visible'>" . $row['points'] . "</td>";
                    echo "</tr>";
                    $classement++; // Incrémente le classement
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="container">
        <a href="inscription.php" class="button-link">S'inscrire</a>
        <a href="connexion.php" class="button-link">Se connecter</a>
    </div>
</body>
</html>
