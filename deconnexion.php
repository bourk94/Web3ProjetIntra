<?php
session_start();
// Supprimes toutes les variables
session_unset();

// Détruire la session
session_destroy();

header("Location: index.php");
?>