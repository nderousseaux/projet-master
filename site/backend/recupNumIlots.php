<?php
// Vérifie que les champs sont présents
if (!(isset($_POST["idUtilisateur"]) && isset($_POST["numChamp"]))) {
	$erreur = array("Erreur", "Champ(s) manquant(s) dans la requête");
	echo json_encode($erreur);
	exit();
}

// Vérifie que les champs sont numériques
if (!is_numeric($_POST["idUtilisateur"])) {
	$erreur = array("Erreur", "Type du numéro d'utilisateur non reconnu");
	echo json_encode($erreur);
	exit();
}
if (!is_numeric($_POST["numChamp"])) {
	$erreur = array("Erreur", "Type du numéro de champ non reconnu");
	echo json_encode($erreur);
	exit();
}

// Connexion à MongoDB
use MongoDB\Driver\Manager;
$uri = "mongodb://localhost:30001";

// Créé le client
$client = new MongoDB\Driver\Manager($uri);

// Défini le filtre
$filtre = ["idAgri" => intval($_POST["idUtilisateur"])];

// Défini la projection
$options = ["projection" => ["champs.ilots" => 1]];

// Créé la requête
$requete = new MongoDB\Driver\Query($filtre, $options);

// Exécute la requête et récupère le résultat
$resultat = $client->executeQuery("data.agriculteur", $requete);

// Traite les données
$ilots = array();
foreach ($resultat as $element) {
	// Accède à la propriété "ilots" dans le champ "champs"
	$ilots = $element->champs->ilots;
}

// Renvoi le nombre d'ilots
$numChamp = $_POST["numChamp"];
if (isset($ilots[$numChamp])) {
	echo json_encode($ilots[$numChamp]);
}
else {
	$erreur = array("Erreur", "Index invalide");
	echo json_encode($erreur);
}