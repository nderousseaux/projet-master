<?php
error_reporting(E_ALL);
ini_set('display_errors',1); 
require_once __DIR__ . '/vendor/autoload.php';
try {
    $client = new MongoDB\Client("mongodb://localhost:30001");

    $dbs = $client->listDatabases();

    echo "Connexion à MongoDB réussie !";

} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Erreur MongoDB : " . $e->getMessage();
}
?>