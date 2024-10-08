<?php
// Script renvoyant le nombre total d'ilots d'un champ 
// Doit être appelé par un script ayant une connexion existante à la bdd

// Check si la connexion à la bdd existe 
if (!(isset($manager))) {
	$erreur = array("Erreur", "Connexion bdd inexistante");
	echo json_encode($erreur);
	exit();
}

// Création de la pipeline pour récupérer le nom d'agriculteur
$pipelineilot = [
	['$match' => [
		'idAgri' => intval($_POST["idUtilisateur"]),
	]]
];

// Création de la commande pour chaque collection
$commandilot = new MongoDB\Driver\Command([
    "aggregate" => "agriculteur",
    "pipeline" => $pipelineilot,
    "cursor" => new stdClass(),
]);

// Exécution de la commande
$cursor = $manager->executeCommand('data', $commandilot);

$ilots = [];
$num = $_POST["numChamp"];
// Traite les données
foreach ($cursor as $element) {
	$ilots = $element->champs->ilots[$num];
}

echo json_encode($ilots);