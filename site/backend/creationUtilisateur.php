<?php

/**
 * Genere une chaine de caractere aleatoire
 * @param length la longueur de la chaine
 * @param keyspace l'alphabet pour construire la chaine
 */
function random_str ($length, $keyspace) {
    $str = '';
    $max = strlen($keyspace) - 1;

    if ($max < 1) {
        throw new Exception('$keyspace doit contenir au moins 2 caracteres');
    }

    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }

    return $str;
}


/**
 * Genere un mot de passe temporaire aleatoire qui repond au criteres suivants:
 * longueur 20; au moins 1 majuscule, minuscule, chiffre et caractere special
 */
function generate_password() {
    $lowercase      = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers        = '0123456789';
    $specialChars   = '!@#$%^&*()_-+=<>?';
    $combinedChars = $lowercase . $uppercase . $numbers . $specialChars;

    // genere le gros du mdp sans garantie de type de caractere
    $password = random_str(16, $combinedChars);

    // ajout d'un caractere de chaque type
    $charsArray = [$lowercase, $uppercase, $numbers, $specialChars];
    foreach($charsArray as $type) {
        $password .= random_str(1, $type);
    }

    // melange les caracteres
    return str_shuffle($password);
}


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
session_start();
$idAgri = $_SESSION["idAgri"];
// $idAgri = 0;

$newCompte = [
    "idUser"    => firstFreeIdUser($mongoClient, $database, $collection),
    "idAgri"    => $idAgri,
    "role"      => $_POST['role'],
    "nom"       => $_POST['nom'],
    "prenom"    => $_POST['prenom'],
    "mail"      => $_POST['courriel'],
    "mdp"       => generate_password(),
    "mdp_temp"  => true,
];

// vérifier qu'il n'y a pas déjà un compte pour cette @ mail
// Défini le filtre
$filtre = [
	"mail" => $_POST["courriel"]
];

// Créé la requête
$requete = new MongoDB\Driver\Query($filtre);

$cursor = $mongoClient->executeQuery("$database.$collection", $requete);

if (!empty($cursor)) { // mail deja existant
    echo "Il existe deja un utilisateur avec cette adresse de courriel";
    exit();
}

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
