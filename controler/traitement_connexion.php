<?php
session_start();
include('../model/config.php');

$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$mot_de_passe = htmlspecialchars($_POST['mot_de_passe'], ENT_QUOTES, 'UTF-8');

$sql = "SELECT id, mot_de_passe FROM utilisateurs WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($utilisateur_id, $mot_de_passe_hache);
        $stmt->fetch();

        if (password_verify($mot_de_passe, $mot_de_passe_hache)) {
            $_SESSION['utilisateur_connecte'] = true;
            $_SESSION['utilisateur_id'] = $utilisateur_id;
            header('Location: ../view/classement.php');
            exit();
        } else {
            // Mot de passe incorrect, stockez un message d'erreur dans une variable de session
            $_SESSION['message'] = 'Mot de passe incorrect.';
            header('Location: ../view/connexion.php');
            exit();
        }
    } else {
        // L'utilisateur avec l'adresse email n'existe pas, stockez un message d'erreur dans une variable de session
        $_SESSION['message'] = "L'utilisateur avec l'adresse email '$email' n'existe pas.";
        header('Location: ../view/connexion.php');
        exit();
    }

    // Fermez l'instruction préparée
    $stmt->close();
} else {
    $errorMessage = "Erreur lors de la préparation de la requête de connexion : " . $conn->error;
    $_SESSION['message'] = htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8');
    echo $errorMessage;
    header('Location: ../view/connexion.php');
    exit();
}

$conn->close();
?>
