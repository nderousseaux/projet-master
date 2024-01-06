<?php

// Récupère les informations du champ pour le tableau
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

// Définition de la pipeline de la requete
// On recupere la valeur la plus recente des capteurs pour chaque ilot
$pipelinetemp = [
	['$match' => [
		'idAgri' => intval($_POST["idUtilisateur"]),
		'idChamps' => intval($_POST["numChamp"])
	]],
	['$sort' => ['date' => -1]],
    ['$group' => [
        '_id' => '$idIlot',
        'temp' => ['$first' => '$temp'],
		'date' => ['$first' => '$date'],
		'idIlot' => ['$first' => '$idIlot']
    ]],
	['$sort' => ['idIlot' => 1]],
];
$pipelinehumi = [
	['$match' => [
		'idAgri' => intval($_POST["idUtilisateur"]),
		'idChamps' => intval($_POST["numChamp"]),
	]],
	['$sort' => ['date' => -1]],
    ['$group' => [
        '_id' => '$idIlot',
        'humi' => ['$first' => '$humi'], 
		'date' => ['$first' => '$date'],
		'idIlot' => ['$first' => '$idIlot']
    ]],
	['$sort' => ['idIlot' => 1]],
];
$pipelinelumi = [
	['$match' => [
		'idAgri' => intval($_POST["idUtilisateur"]),
		'idChamps' => intval($_POST["numChamp"]),
	]],
	['$sort' => ['date' => -1]],
    ['$group' => [
        '_id' => '$idIlot',
        'lumi' => ['$first' => '$lumi'], 
		'date' => ['$first' => '$date'],
		'idIlot' => ['$first' => '$idIlot']
    ]],
	['$sort' => ['idIlot' => 1]],
];

// Création de la commande pour chaque collection
$commandtemp = new MongoDB\Driver\Command([
    "aggregate" => "temp",
    "pipeline" => $pipelinetemp,
    "cursor" => new stdClass(),
]);
$commandhumi = new MongoDB\Driver\Command([
    "aggregate" => "humi",
    "pipeline" => $pipelinehumi,
    "cursor" => new stdClass(),
]);
$commandlumi= new MongoDB\Driver\Command([
    "aggregate" => "lumi",
    "pipeline" => $pipelinelumi,
    "cursor" => new stdClass(),
]);

// Exécution de la commande d'agrégation
$curdate = new DateTime("Europe/Paris");
$cursortemp = $manager->executeCommand('data', $commandtemp);
$cursorhumi = $manager->executeCommand('data', $commandhumi);
$cursorlumi = $manager->executeCommand('data', $commandlumi);

// Traitement des données
$lastdate = null;
$temps = []; 
$humis = []; 
$lumis = []; 
$states = [];
$dates = [];
$counttemp = 0;
$counthumi = 0;
$countlumi = 0;

foreach ($cursortemp as $element) {
	if (isset($element)) {
		if ($element->temp == null) {
			$temps[] = "N/A";
		} 
		else {
			$temps[] = $element->temp;
		}
	
		$dbdate = $element->date->toDateTime()->
			setTimezone(new DateTimeZone("Europe/Paris"));
		$ilot = intval($element->_id);
		// On garde un date pour chaque ilot
		$dates[] = $dbdate;
		// Date sous format millisecondes depuis l'epoch
		// Si dernier valeur du capteur date de plus de 30 minutes, ko
		$diff = $curdate->diff($dbdate);
		$gap = $diff->days * 24 * 60 + $diff->h * 60 + $diff->i;
		if ($gap > 30) {
			$state[] = "KO";
		}
		else {
			$state[] = "OK";
		}
		$counttemp = $counttemp + 1;
	}
	//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd temp");
		echo json_encode($erreur);
		exit();
	}
}

foreach ($cursorhumi as $element) {
	if (isset($element)) {
		if ($element->humi == null) {
			$humis[] = "N/A";
		} 
		else {
			$humis[] = $element->humi;
		}
		$dbdate = $element->date->toDateTime()->
		setTimezone(new DateTimeZone("Europe/Paris"));
		$ilot = intval($element->_id);

		// On garde la date la plus nouvelle pour chaque ilot
		if ($dates[$ilot] > $dbdate)
			$dates[$ilot] = $dbdate;

		// Si dernier valeur du capteur date de plus de 30 minutes, ko
		$diff = $curdate->diff($dbdate);
		$gap = $diff->days * 24 * 60 + $diff->h * 60 + $diff->i;
		if ($gap > 30) {
			$state[$ilot] = "KO";
		}
		$counthumi = $counthumi + 1;
	}
		//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd temp");
		echo json_encode($erreur);
		exit();
	}
}

foreach ($cursorlumi as $element) {
	if (isset($element)) {
		if ($element->lumi == null) {
			$lumis[] = "N/A";
		} 
		else {
			$lumis[] = $element->lumi;
		}
		$dbdate = $element->date->toDateTime()->
			setTimezone(new DateTimeZone("Europe/Paris"));
		$ilot = intval($element->_id);
		
		// On garde la date la plus nouvelle pour chaque ilot
		if ($dates[$ilot] < $dbdate)
			$dates[$ilot] = $dbdate;

		// Si dernier valeur du capteur date de plus de 30 minutes, ko
		$diff = $curdate->diff($dbdate);
		$gap = $diff->days * 24 * 60 + $diff->h * 60 + $diff->i;
		if ($gap > 30) {
			$state[$ilot] = "KO";
		}
		$countlumi = $countlumi + 1;
	}
	//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd temp");
		echo json_encode($erreur);
		exit();
	}
}

// Construction du tableau 
// [renumilot(s), date_dernier_val,temp, humi, lumi]
if (!($counttemp == $counthumi && $counttemp == $countlumi)) {
	echo json_encode("Erreur", "comptes différents");
	echo json_encode($counttemp, $counthumi, $countlumi);
}

$res = [];
// Renvoi du resultat
for ($i = 0; $i < $counttemp; $i++) {
	$fd = $dates[$i]->format("Y-m-d H:i");
	$rest = round(floatval($temps[$i]),1);
	$resh = round(floatval($humis[$i]),1);
	$resl = round(floatval($lumis[$i]),1);
	$res[] = array($i, $fd, $state[$i], $rest, $resh, $resl);
}
echo json_encode($res);