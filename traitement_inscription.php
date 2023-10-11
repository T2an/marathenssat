<?php
session_start();
include('config.php');

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

// Vérifiez d'abord si l'adresse email est déjà utilisée
$sql_verif_email = "SELECT id FROM utilisateurs WHERE email = '$email'";
$result_verif_email = $conn->query($sql_verif_email);

if ($result_verif_email->num_rows > 0) {
    // L'adresse email est déjà utilisée, affichez un message d'erreur
    $_SESSION['message'] = "L'adresse email '$email' est déjà enregistrée. Utilisez une autre adresse email.";
    header('Location: inscription.php');
} else {
    // L'adresse email n'est pas utilisée, procédez à l'inscription
    $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES ('$nom', '$prenom', '$email', '$mot_de_passe')";

    if ($conn->query($sql) === TRUE) {
        // Redirigez l'utilisateur vers la page classement.php si l'inscription réussie
        $_SESSION['utilisateur_connecte'] = true;
        $_SESSION['utilisateur_id'] = $conn->insert_id; // Récupère l'ID de l'utilisateur nouvellement inscrit
        header('Location: classement.php');
    } else {
        // Affichez un message d'erreur générique et redirigez l'utilisateur vers la page d'inscription
        $_SESSION['message'] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
        header('Location: inscription.php');
    }
}

$conn->close();
?>
