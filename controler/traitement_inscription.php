<?php
session_start();
include('../model/config.php');

$nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
$prenom = htmlspecialchars($_POST['prenom'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$mot_de_passe = password_hash(htmlspecialchars($_POST['mot_de_passe'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT);

// Vérifiez d'abord si l'adresse email est déjà utilisée
$sql_verif_email = "SELECT id FROM utilisateurs WHERE email = ?";
$stmtVerifEmail = $conn->prepare($sql_verif_email);

if ($stmtVerifEmail) {
    $stmtVerifEmail->bind_param("s", $email);
    $stmtVerifEmail->execute();
    $stmtVerifEmail->store_result();

    if ($stmtVerifEmail->num_rows > 0) {
        // L'adresse email est déjà utilisée, affichez un message d'erreur
        $_SESSION['message'] = "L'adresse email '$email' est déjà enregistrée. Utilisez une autre adresse email.";
        header('Location: ../view/inscription.php');
        exit();
    }
} else {
    $errorMessage = "Erreur lors de la préparation de la requête de vérification d'email : " . $conn->error;
    $_SESSION['message'] = htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8');
    echo $errorMessage;
    header('Location: ../view/inscription.php');
    exit();
}

// L'adresse email n'est pas utilisée, procédez à l'inscription
$sql_insert_utilisateur = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
$stmtInsertUtilisateur = $conn->prepare($sql_insert_utilisateur);

if ($stmtInsertUtilisateur) {
    $stmtInsertUtilisateur->bind_param("ssss", $nom, $prenom, $email, $mot_de_passe);

    if ($stmtInsertUtilisateur->execute()) {
        // Récupérez l'ID de l'utilisateur nouvellement inscrit
        $utilisateur_id = $stmtInsertUtilisateur->insert_id;

        // Insérez une ligne dans la table classement avec un nombre de points initialisé à 0
        $sql_insert_classement = "INSERT INTO classement (utilisateur_id, points) VALUES (?, 0)";
        $stmtInsertClassement = $conn->prepare($sql_insert_classement);

        if ($stmtInsertClassement) {
            $stmtInsertClassement->bind_param("i", $utilisateur_id);

            if ($stmtInsertClassement->execute()) {
                // Redirigez l'utilisateur vers la page classement.php si l'inscription réussie
                $_SESSION['utilisateur_connecte'] = true;
                $_SESSION['utilisateur_id'] = $utilisateur_id;
                header('Location: ../view/classement.php');
                exit();
            } else {
                // En cas d'erreur lors de l'insertion dans la table classement, affichez un message d'erreur et redirigez l'utilisateur vers la page d'inscription
                $_SESSION['message'] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
                header('Location: ../view/inscription.php');
            }
        } else {
            $errorMessage = "Erreur lors de la préparation de la requête d'insertion dans la table classement : " . $conn->error;
            $_SESSION['message'] = htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8');
            echo $errorMessage;
            header('Location: ../view/inscription.php');
        }
    } else {
        // Affichez un message d'erreur générique et redirigez l'utilisateur vers la page d'inscription
        $_SESSION['message'] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
        header('Location: ../view/inscription.php');
    }
} else {
    $errorMessage = "Erreur lors de la préparation de la requête d'insertion dans la table utilisateurs : " . $conn->error;
    $_SESSION['message'] = htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8');
    echo $errorMessage;
    header('Location: ../view/inscription.php');
    exit();
}

$conn->close();
?>
