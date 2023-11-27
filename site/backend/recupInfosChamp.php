<?php

// Récupère les informations des capteurs du champ envoyés dans la requête
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

// Défini la projection
$options = ["projections" => ["dates" => 1]];

// Créé la requête
$requete = new MongoDB\Driver\Query($filtre, $options);
$curdate = new DateTime("UTC");

// Exécute la requête pour chaque type de capteur et récupère le résultat
$resultattemp = $client->executeQuery("data.temp", $requete);
$resultathumi = $client->executeQuery("data.humi", $requete);
$resultatlumi = $client->executeQuery("data.lumi", $requete);

// Traitement des donnée
$count = 0;
$countko = 0;

foreach ($resultattemp as $element) {
	if (isset($element)) {
		foreach($element->dates as $dbdate) {
			// Date sous format millisecondes depuis l'epoch
			$gap = $curdate->diff($dbdate->toDateTime());

			// Si dernier valeur du capteur date de plus de 30 minutes, ko
			if ($gap->format("%i") > 30)
				$countko = $countko + 1;
			$count = $count + 1;
		}
	}
	//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd");
		echo json_encode($erreur);
		exit();
	}
}

foreach ($resultathumi as $element) {
	if (isset($element)) {
		foreach($element->dates as $dbdate) {
			// Date sous format millisecondes depuis l'epoch
			$gap = $curdate->diff($dbdate->toDateTime());

			// Si dernier valeur du capteur date de plus de 30 minutes, ko
			if ($gap->format("%i") > 30)
				$countko = $countko + 1;
			$count = $count + 1;
		}
	}
	//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd");
		echo json_encode($erreur);
		exit();
	}
}

foreach ($resultatlumi as $element) {
	if (isset($element)) {
		foreach($element->dates as $dbdate) {
			// Date sous format millisecondes depuis l'epoch
			$gap = $curdate->diff($dbdate->toDateTime());

			// Si dernier valeur du capteur date de plus de 30 minutes, ko
			if ($gap->format("%i") > 30)
				$countko = $countko + 1;
			$count = $count + 1;
		}
	}
	//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd");
		echo json_encode($erreur);
		exit();
	}
}

$status = ($countko == $count ? "KO" :"OK");

// Renvoi le statut des capteurs du champ
$localDate = $curdate->setTimezone(new DateTimeZone("Europe/Paris"))->
	format("Y-m-d H:i:s");
echo json_encode(array($status, $count-$countko , $count, $localDate));
