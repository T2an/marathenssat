<?php
// Chemin vers le fichier .env
$envFilePath = '/var/www/monsite/var.env';

// Vérifiez si le fichier .env existe
if (file_exists($envFilePath)) {
    // Chargez les variables d'environnement à partir du fichier .env
    $envVariables = parse_ini_file($envFilePath);

    // Assurez-vous que les variables nécessaires existent
    if (
        isset($envVariables['DB_HOST']) &&
        isset($envVariables['DB_USER']) &&
        isset($envVariables['DB_PASS']) &&
        isset($envVariables['DB_NAME'])
    ) {
        $servername = $envVariables['DB_HOST'];
        $username = $envVariables['DB_USER'];
        $password = $envVariables['DB_PASS'];
        $dbname = $envVariables['DB_NAME'];

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }
    } else {
        die("Le fichier .env ne contient pas toutes les variables nécessaires.");
    }
} else {
    die("Le fichier .env n'a pas été trouvé." . $envFilePath);
}

?>
