<?php
session_start();
session_destroy();
header('Location: ../view/connexion.php');
exit(); // Assure que le script s'arrête ici pour éviter toute exécution supplémentaire.
?>
