<?php

// TODO à compléter pour les autres updates d'infos
// et rédiriger vers index.php (dans js) quand chgt mdp tmp

// Vérifie que toutes les infos sont présentes
if (!(isset($_POST["idUser"]) && isset($_POST["mdp"]))) {
	$erreur = array("Erreur", "Infos manquantes dans la requête");
	echo json_encode($erreur);
	exit();
}

// Infos de connexion à la BDD
$hosts = [
    "mongo1:30001",
    "mongo2:30002",
    "mongo3:30003"
];
$connectionString = implode(",", $hosts);

$database       = "data";
$collection     = "compte";
$replicaSetName = "rs0";

// uri de connnexion
use MongoDB\Driver\Manager;
//$uri = "mongodb://$connectionString/?replicaSet=$replicaSetName";
$uri = getenv('MONGODB_URL');

// Créer le client
try {
    $mongoClient = new MongoDB\Driver\Manager($uri);
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
    die("Failed to connect to MongoDB: " . $e->getMessage());
}

$idUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_STRING);

// Creer le filtre
$filter = ['idUser' => $idUser];

$newMdp = password_hash($_POST["mdp"], PASSWORD_DEFAULT);

// operation de mise à jour
$update = ['$set' => ['mdp' => $newMdp]];

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update($filter, $update);

// Executer l'operation
try {
    $result = $mongoClient->executeBulkWrite("$database.$collection", $bulk);
    echo "Update successful";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Update failed: ", $e->getMessage();
}

