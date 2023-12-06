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
$manager = new MongoDB\Driver\Manager("mongodb://localhost:30001");

// Définition de la pipeline de la requete
// On match sur l'agriculteur et le champ, puis on tri par date decroissante
// et on recupere la premiere occurence pour chaque ilot (la plus recente)
$pipelinetemp = [
	['$match' => [
		'idAgri' => intval($_POST["idUtilisateur"]),
		'idChamps' => intval($_POST["numChamp"])
	]],
	['$sort' => ['date' => -1]],
    ['$group' => [
        '_id' => '$idIlot',
        'temp' => ['$first' => '$temp']
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
    ]],
	['$sort' => ['idIlot' => 1]],
];

// Création de la commande pour chaque collection
$commandtemp = new MongoDB\Driver\Command([
    "aggregate" => "temp",
    "pipeline" => $pipelinetemp,
    "cursor" => new stdClass(), // Spécifier un curseur par défaut
]);
$commandhumi = new MongoDB\Driver\Command([
    "aggregate" => "humi",
    "pipeline" => $pipelinehumi,
    "cursor" => new stdClass(), // Spécifier un curseur par défaut
]);
$commandlumi= new MongoDB\Driver\Command([
    "aggregate" => "lumi",
    "pipeline" => $pipelinelumi,
    "cursor" => new stdClass(), // Spécifier un curseur par défaut
]);

// Exécution de la commande d'agrégation
$cursortemp = $manager->executeCommand('data', $commandtemp);
$cursorhumi = $manager->executeCommand('data', $commandhumi);
$cursorlumi = $manager->executeCommand('data', $commandlumi);

// Traitement des données
$temps = 0.0;
$humis = 0.0;
$lumis = 0;
$count = 0;

// Calcul moyenne températures
foreach ($cursortemp as $element) {
	//S'il y a des valeurs on les additionne pour la moyenne
	if (isset($element)) {
		$temps = $temps + floatval($element->temp);
		$count += 1;
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
foreach ($cursorhumi as $element) {
	//S'il y a des valeurs on les additionne pour la moyenne
	if (isset($element)) {
		$humis = $humis + floatval($element->humi);
		$count += 1;
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
foreach ($cursorlumi as $element) {
	//S'il y a des valeurs on les additionne pour la moyenne
	if (isset($element)) {
		$lumis = $lumis + floatval($element->lumi);
		$count += 1;
	}
	// Si pas de retour de la bdd, erreur
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