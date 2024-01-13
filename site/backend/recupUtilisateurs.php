<?php
/**
 * Récupère la liste des utilisateurs de cet agriculteur (prénom et nom)
 */

// Vérifie que toutes les infos sont présentes
if (!(isset($_POST["idUtilisateur"]))) {
	$erreur = array("Erreur", "Infos manquantes dans la requête");
	echo json_encode($erreur);
	exit();
}

// table à accéder
$database       = "data";
$collection     = "compte";

// uri de connnexion
use MongoDB\Driver\Manager;
$uri = getenv('MONGODB_URL');

// Créer le client
try {
    $mongoClient = new MongoDB\Driver\Manager($uri);
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
    die("Failed to connect to MongoDB: " . $e->getMessage());
}

$filter = ['idAgri' => intval($_POST["idUtilisateur"])];
$options = [
    'projection' => ['idUser' => 1, 'nom' => 1, 'prenom' => 1, '_id' => 0],
];

$query = new MongoDB\Driver\Query($filter, $options);

$users = [];
try {
    $cursor = $mongoClient->executeQuery("$database.$collection", $query);

    // copie des users dans une liste
    foreach ($cursor as $user) {
		array_push($users, [$user->idUser, $user->prenom, $user->nom]);
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Query failed: " . $e->getMessage();
}

// renvoie la liste
echo json_encode($users);

// $fichierDonneesUtilisateurs = "json/utilisateurs.json";
// if (file_exists($fichierDonneesUtilisateurs)) {
// 	echo file_get_contents($fichierDonneesUtilisateurs);
// }
// else {
// 	$erreur = array("Erreur", "Le fichier n'existe pas");
// 	echo json_encode($erreur);
// 	exit();
// }