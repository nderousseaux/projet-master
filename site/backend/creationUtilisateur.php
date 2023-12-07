<?php

// Requete pour récupérer le plus grand idUser pour trouver le 1er libre
function firstFreeIdUser($mongoClient, $database, $collection) {
    $filter = [];
    $sort = ['idUser' => -1];   // tri par ordre décroissant
    $options = ['sort' => $sort, 'limit' => 1];  // on veut 1 seul elt (le plus grand)

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $mongoClient->executeQuery("$database.$collection", $query);
    $highestUser = current($cursor->toArray());
    $nextIdUser = $highestUser ? $highestUser->idUser + 1 : 0;

    return $nextIdUser;
}


// Vérifie que toutes les infos sont présentes
if (!(isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["role"]) && isset($_POST["courriel"]))) {
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
$uri = "mongodb://localhost:30001";

// Créer le client
try {
    $mongoClient = new MongoDB\Driver\Manager($uri);
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
    die("Failed to connect to MongoDB: " . $e->getMessage());
}


// Récupérer IdAgri de l'utilisateur actuel pour ajouter nouveau user au même agri
// TODO
$idAgri = 0;

$newCompte = [
    "idUser"    => firstFreeIdUser($mongoClient, $database, $collection),
    "idAgri"    => $idAgri,
    "role"      => $_POST['role'],
    "nom"       => $_POST['nom'],
    "prenom"    => $_POST['prenom'],
    "mail"      => $_POST['courriel'],
    "mdp"       => "pasSafeDuTout" // TODO remplacer par un mdp aléatoire provisoire à changer à la première co ?
];

// Ajouter le nouvel utilisateur
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);

$insert = new MongoDB\Driver\BulkWrite();
$insert->insert($newCompte);

try {
    $result = $mongoClient->executeBulkWrite("$database.$collection", $insert, $writeConcern);
    echo "Document inserted successfully. Inserted document ID: " . $result->getInsertedCount();
} catch (MongoDB\Driver\Exception\BulkWriteException $e) {
    die("Error inserting document: " . $e->getMessage());
}

?>