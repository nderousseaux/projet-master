<?php
// Script permettant de récupérer le nom d'utilisateur
// Doit être appelé par un script ayant une connexion existante à la bdd

// Check si la connexion à la bdd existe 
if (!(isset($manager))) {
	$erreur = array("Erreur", "Connexion bdd inexistante");
	echo json_encode($erreur);
	exit();
}

// Création de la pipeline pour récupérer le nom d'agriculteur
$pipelineagri = [
	['$match' => [
		'idAgri' => intval($_POST["idUtilisateur"]),
	]],
    ['$group' => [
        'nomAgri' => 'nomAgri'
    ]]
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
echo $resultat;