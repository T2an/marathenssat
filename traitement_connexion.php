<?php
session_start();
include('config.php');

$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe'];

$sql = "SELECT * FROM utilisateurs WHERE email = '$email' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($mot_de_passe, $row['mot_de_passe'])) {
        $_SESSION['utilisateur_connecte'] = true;
        $_SESSION['utilisateur_id'] = $row['id'];
        header('Location: classement.php');
    } else {
        // Mot de passe incorrect, stockez un message d'erreur dans une variable de session
        $_SESSION['message'] = 'Mot de passe incorrect.';
        header('Location: connexion.php');
    }
} else {
    // L'utilisateur avec l'adresse email n'existe pas, stockez un message d'erreur dans une variable de session
    $_SESSION['message'] = "L'utilisateur avec l'adresse email '$email' n'existe pas.";
    header('Location: connexion.php');
}

$conn->close();
?>
