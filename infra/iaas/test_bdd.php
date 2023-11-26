<?php
try {
    $client = new MongoDB\Client("mongodb://localhost:30001");

    echo "Connexion à MongoDB réussie !";

} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Erreur MongoDB : " . $e->getMessage();
}
?>
