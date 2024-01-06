<?php
// Récupère les informations des capteurs du champ envoyés dans la requête
// Doit être appelé par un script ayant une connexion existante à la bdd

// Check si la connexion à la bdd existe 
if (!(isset($manager))) {
	$erreur = array("Erreur", "Connexion bdd inexistante");
	echo json_encode($erreur);
	exit();
}

// Récupération de la timezone du champ du client
$curdate = new DateTime();

// Défini la pipeline de la requete
// On recupere la date de derniere maj des capteurs de chaque ilot
$pipeline = [
	['$match' => [
		'idAgri' => intval($_POST["idUtilisateur"]),
		'idChamps' => intval($_POST["numChamp"])
	]],
	['$sort' => ['date' => -1]],
    ['$group' => [
        '_id' => '$idIlot',
        'date' => ['$first' => '$date']
    ]],
];

// Création de la commande pour chaque collection
$commandtemp = new MongoDB\Driver\Command([
    "aggregate" => "temp",
    "pipeline" => $pipeline,
    "cursor" => new stdClass(),
]);
$commandhumi = new MongoDB\Driver\Command([
    "aggregate" => "humi",
    "pipeline" => $pipeline,
    "cursor" => new stdClass(),
]);
$commandlumi= new MongoDB\Driver\Command([
    "aggregate" => "lumi",
    "pipeline" => $pipeline,
    "cursor" => new stdClass(),
]);

// Exécution de la commande d'agrégation
$cursortemp = $client->executeCommand('data', $commandtemp);
$cursorhumi = $client->executeCommand('data', $commandhumi);
$cursorlumi = $client->executeCommand('data', $commandlumi);

// Traitement des donnée
$count = 0;
$countko = 0;

foreach ($cursortemp as $element) {
	if (isset($element)) {
		$dbdate = $element->date;
		// Date sous format millisecondes depuis l'epoch
		$gap = $curdate->diff($dbdate->toDateTime());
		// Si dernier valeur du capteur date de plus de 30 minutes, ko
		if ($gap->format("%i") > 30)
			$countko = $countko + 1;
		$count = $count + 1;
	}
	//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd");
		echo json_encode($erreur);
		exit();
	}
}

foreach ($cursorhumi as $element) {
	if (isset($element)) {
		$dbdate = $element->date;
		// Date sous format millisecondes depuis l'epoch
		$gap = $curdate->diff($dbdate->toDateTime());
		// Si dernier valeur du capteur date de plus de 30 minutes, ko
		if ($gap->format("%i") > 30)
			$countko = $countko + 1;
		$count = $count + 1;
	}
	//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd");
		echo json_encode($erreur);
		exit();
	}
}

foreach ($cursorlumi as $element) {
	if (isset($element)) {
		$dbdate = $element->date;
		// Date sous format millisecondes depuis l'epoch
		$gap = $curdate->diff($dbdate->toDateTime());
		// Si dernier valeur du capteur date de plus de 30 minutes, ko
		if ($gap->format("%i") > 30)
			$countko = $countko + 1;
		$count = $count + 1;
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
echo array($status, $count-$countko , $count, $localDate);