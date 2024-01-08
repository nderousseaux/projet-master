<?php
error_reporting(E_ALL);
ini_set('display_errors',1); 
try {
    $client = new MongoDB\Client("mongodb://localhost:30001");

    $dbs = $client->listDatabases();

    echo "Connexion à MongoDB réussie !";

} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Erreur MongoDB : " . $e->getMessage();
}
?>
