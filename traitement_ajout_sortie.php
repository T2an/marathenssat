<?php
session_start();
include('config.php');

// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION['utilisateur_connecte']) || !$_SESSION['utilisateur_connecte']) {
    header('Location: connexion.php');
    exit();
}

// Récupérez les données soumises depuis le formulaire
$km = $_POST['km'];
$nom = $_POST['nom'];
$date = $_POST['date']; // Récupération de la date

// Générez automatiquement des codes de course et de compensation aléatoires
$code_course = genererCodeAleatoire(5);
$code_compense = genererCodeAleatoire(5);

// Générateur de code aléatoire
function genererCodeAleatoire($longueur) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $code = '';
    $max = strlen($caracteres) - 1;

    for ($i = 0; $i < $longueur; $i++) {
        $code .= $caracteres[mt_rand(0, $max)];
    }

    return $code;
}

// Vérifiez si un fichier a été téléchargé
if (isset($_FILES['image'])) {
    $file = $_FILES['image'];
    
    // Vérifiez s'il n'y a pas d'erreur lors du téléchargement
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Obtenez le nom temporaire du fichier
        $tempName = $file['tmp_name'];
        
        // Générez un nom unique pour le fichier
        $fileName = 'images/' . uniqid() . '.png';
        
        // Déplacez le fichier temporaire vers le dossier "images"
        move_uploaded_file($tempName, $fileName);
        
        // Préparez la requête SQL pour insérer la sortie dans la table "sorties" avec le nom du fichier
        $sql_insert_sortie = "INSERT INTO sorties (km, nom, code_course, code_compense, date, image) VALUES (?, ?, ?, ?, ?, ?)";

        // Utilisez une instruction préparée pour éviter les injections SQL
        $stmt = $conn->prepare($sql_insert_sortie);
        if ($stmt) {
            // Liez les paramètres et leurs valeurs
            $stmt->bind_param("dsssss", $km, $nom, $code_course, $code_compense, $date, $fileName);

            // Exécutez la requête préparée
            if ($stmt->execute()) {
                // Sortie ajoutée avec succès, vous pouvez rediriger l'utilisateur ou afficher un message de réussite
                $_SESSION['message'] = 'Succès de ajout';
                header('Location: administration.php'); // Redirection vers la page d'administration
                exit();
            } else {
                $_SESSION['message'] = 'Erreur lors de ajout de la sortie';
                echo "Erreur lors de l'ajout de la sortie : " . $stmt->error;
            }

            // Fermez l'instruction préparée
            $stmt->close();
        } else {
            echo "Erreur lors de la préparation de la requête : " . $conn->error;
        }
    } else {
        $_SESSION['message'] = 'Erreur lors du téléchargement de l\'image : ' . $file['error'];
        header('Location: administration.php'); // Redirection vers la page d'administration en cas d'erreur
        exit();
    }
} else {
    $_SESSION['message'] = 'Aucune image téléchargée.';
    header('Location: administration.php'); // Redirection vers la page d'administration si aucune image n'a été téléchargée
    exit();
}

// Fermez la connexion à la base de données
$conn->close();
?>
