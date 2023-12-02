<?php

// Récupère les moyennes des mesures du champ envoyés dans la requête
// Vérification de leur existence
if (!(isset($_POST["idUtilisateur"]) && isset($_POST["numChamp"]))) {
	$erreur = array("Erreur", "Champ(s) manquant(s) dans la requête");
	echo json_encode($erreur);
	exit();
}

// Vérifie que les entrées sont de type numérique
if (!(is_numeric($_POST["numChamp"]))) {
	$erreur = array("Erreur", "Type du numéro de champ non reconnu");
	echo json_encode($erreur);
	exit();
}
if (!(is_numeric($_POST["idUtilisateur"]))) {
	$erreur = array("Erreur", "Type du numéro d'utilisateur non reconnu");
	echo json_encode($erreur);
	exit();
}
if (!(is_numeric($_POST["idUtilisateur"]))) {
	$erreur = array("Erreur", "Type du numéro d'utilisateur non reconnu");
	echo json_encode($erreur);
	exit();
}

// Connexion à MongoDB
use MongoDB\Driver\Manager;
$uri = "mongodb://localhost:30001";

// Créé le client
$client = new MongoDB\Driver\Manager($uri);

// Défini le filtre
$filtre = [
	"idAgri" => intval($_POST["idUtilisateur"]),
	"idChamps" => intval($_POST["numChamp"]),
];

// Défini les projections
$options = ["projection" => ["valeurs" => 1]];

// Créé la requête
$requete = new MongoDB\Driver\Query($filtre, $options);

// Exécute la requête pour chaque type de capteur et récupère le résultat
$resultattemp = $client->executeQuery("data.temp", $requete);
$resultathumi = $client->executeQuery("data.humi", $requete);
$resultatlumi = $client->executeQuery("data.lumi", $requete);

// Traitement des données
$count = 0;
$temps = 0.0;
$humis = 0.0;
$lumis = 0;

// Calcul moyenne températures
foreach ($resultattemp as $element) {
	//S'il y a des valeurs on les additionne pour la moyenne
	if (isset($element)) {
		foreach ($element->valeurs as $mesure) {
			$temps = $temps + floatval($mesure);
			$count += 1;
		}
	}
	// Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd");
		echo json_encode($erreur);
		exit();
	}
}
$moytemp = $temps / $count;

$count = 0;
// Calcul moyenne humidite
foreach ($resultathumi as $element) {
	// S'il y a des valeurs on les additionne pour la moyenne
	if (isset($element)) {
		foreach ($element->valeurs as $mesure) {
			$humis = $humis + floatval($mesure);
			$count += 1;
		}
	}
	// Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd");
		echo json_encode($erreur);
		exit();
	}
}
$moyhumi = $humis / $count;

$count = 0;
// Calcul moyenne luminosite
foreach ($resultatlumi as $element) {
	// S'il y a des valeurs on les additionne pour la moyenne
	if (isset($element)) {
		foreach ($element->valeurs as $mesure) {
			$lumis = $lumis + floatval($mesure);
			$count += 1;
		}
	}
	//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd");
		echo json_encode($erreur);
		exit();
	}
}
$moylumi = $lumis / $count;

// Renvoi les moyennes de temperature, humidite et luminosite
$moytemp = round($moytemp, 1);
$moyhumi = round($moyhumi, 1);
$moylumi = round($moylumi, 1);

echo json_encode(array($moytemp, $moyhumi, $moylumi));