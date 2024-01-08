<?php
// Script permettant de récupérer le nom d'utilisateur
// Doit être appelé par un script ayant une connexion existante à la bdd

// Check si la connexion à la bdd existe 
if (!(isset($manager)) && !(isset($_POST["idUtilisateur"]))) {
	$erreur = array("Erreur", "Paramètres manquants");
	echo json_encode($erreur);
	exit();
}

$notload = false;
// Création connexion bdd si inexistante
if (!isset($manager)) {
	$uri = "mongodb://localhost:30001";
	$manager = new MongoDB\Driver\Manager($uri);
	$notload = true;
}

// Création de la pipeline pour récupérer le nom d'agriculteur
$pipelineagri = [
	['$match' => [
		'idAgri' => intval($_POST["idUtilisateur"]),
	]],
];

// Création de la commande pour chaque collection
$commandagri = new MongoDB\Driver\Command([
    "aggregate" => "agriculteur",
    "pipeline" => $pipelineagri,
    "cursor" => new stdClass(),
]);

// Exécution de la commande
$cursor = $manager->executeCommand('data', $commandagri);

// Traite les données
foreach ($cursor as $element) {
	$resultat = $element->nomAgri;
}

// Renvoi le nom d'utilisateur
echo ($notload ? json_encode($resultat) : $resultat);