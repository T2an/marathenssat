<?php
session_start();
include('config.php');

// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION['utilisateur_connecte']) || !$_SESSION['utilisateur_connecte']) {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérez le code saisi depuis le formulaire
    $codeSaisi = $_POST['code'];

    // Récupérez l'ID de l'utilisateur connecté
    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Vérifiez si le couple "sortie_id" et "utilisateur_id" existe déjà dans la table "participations"
    $sql_verifier_participation_existe = "SELECT * FROM participations WHERE utilisateur_id = ? AND sortie_id IN (SELECT id FROM sorties WHERE code_course = ? OR code_compense = ?)";
    $stmtVerifParticipationExiste = $conn->prepare($sql_verifier_participation_existe);

    if ($stmtVerifParticipationExiste) {
        $stmtVerifParticipationExiste->bind_param("iss", $utilisateur_id, $codeSaisi, $codeSaisi);
        $stmtVerifParticipationExiste->execute();
        $stmtVerifParticipationExiste->store_result();

        if ($stmtVerifParticipationExiste->num_rows > 0) {
            // L'utilisateur a déjà participé à cette sortie
            $_SESSION['message'] = "Vous avez déjà participé à cette sortie.";
            header('Location: calcul_points.php'); // Rediriger vers la page des sorties
            exit();
        }

        // Fermez l'instruction préparée
        $stmtVerifParticipationExiste->close();
    } else {
        $errorMessage = "Erreur lors de la préparation de la requête de vérification de participation : " . $conn->error;
        $_SESSION['message'] = $errorMessage;
        echo $errorMessage;
        header('Location: sorties.php'); // Rediriger vers la page des sorties
        exit();
    }

    // Vérifiez si le code saisi correspond à un code de course ou de compensation dans la base de données
    $sql_verifier_code = "SELECT id, code_course, code_compense FROM sorties WHERE code_course = ? OR code_compense = ?";
    $stmt = $conn->prepare($sql_verifier_code);

    if ($stmt) {
        $stmt->bind_param("ss", $codeSaisi, $codeSaisi);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($sortieId, $codeCourse, $codeCompense);

            while ($stmt->fetch()) {
                // Déterminez la valeur de l'attribut "compense" en fonction du code saisi
                $compense = ($codeSaisi === $codeCompense) ? 1 : 0;

                // Insérez une entrée dans la table "participations"
                $sql_inserer_participation = "INSERT INTO participations (utilisateur_id, sortie_id, compense) VALUES (?, ?, ?)";
                $stmtInsert = $conn->prepare($sql_inserer_participation);

                if ($stmtInsert) {
                    $stmtInsert->bind_param("iii", $utilisateur_id, $sortieId, $compense);

                    if ($stmtInsert->execute()) {
                        // La participation a été enregistrée avec succès
                        $_SESSION['message'] = "La participation a été enregistrée avec succès";
                        header('Location: calcul_points.php'); // Appel du script calcul_points.php
                        exit();
                    } else {
                        $_SESSION['message'] = "Erreur lors de l'insertion de la participation : " . $stmtInsert->error;
                        echo "Erreur lors de l'insertion de la participation : " . $stmtInsert->error;
                        header('Location: sorties.php'); // Rediriger vers la page des sorties
                        exit();

                    }
                } else {
                    $_SESSION['message'] = "Erreur lors de la préparation de la requête d'insertion : " . $conn->error;
                    echo "Erreur lors de la préparation de la requête d'insertion : " . $conn->error;
                    header('Location: sorties.php'); // Rediriger vers la page des sorties
                    exit();
                }
            }
        } else {  
            $_SESSION['message'] = "Code invalide";
            header('Location: sorties.php'); // Rediriger vers la page des sorties
            exit();
        }

        // Fermez l'instruction préparée
        $stmt->close();
    } else {
        echo "Erreur lors de la préparation de la requête : " . $conn->error;
    }

    // Fermez la connexion à la base de données
    $conn->close();
} else {
    // Si la page est accédée par une méthode GET (non soumise), redirigez l'utilisateur
    header('Location: sorties.php');
    exit();
}
?>
