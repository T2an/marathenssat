<?php
session_start();
session_destroy();
header('Location: connexion.php');
exit(); // Assure que le script s'arrête ici pour éviter toute exécution supplémentaire.
?>
