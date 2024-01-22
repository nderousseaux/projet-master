<?php
// Script de récupération de l'historique des données 
// Effectue la vérification des paramètres et l'initialisation 
// de la connexion vers la base de données MongoDB
use MongoDB\Driver\Manager;

// Chemin de connexion vers la base de données
$uri = getenv('MONGODB_URL');

// Vérification des paramètres d'appel
if (!(isset($_POST["idUtilisateur"])) || !(isset($_POST["champ"])) 
|| !(isset($_POST["type"])) || !(isset($_POST["duree"])) 
|| !(isset($_POST["ilot"]))) {
	$erreur = array("Erreur", "Champ(s) manquant(s) dans la requête");
	echo json_encode($erreur);
	exit();
}

if (!(is_numeric($_POST["idUtilisateur"]))) {
    $erreur = array("Erreur", "Identifiant de type incompatible");
    echo json_encode($erreur);
    exit();
}

if (!(is_numeric($_POST["champ"]))) {
    $erreur = array("Erreur", "Numéro de champ de type incompatible");
    echo json_encode($erreur);
    exit();
}

if (!(is_numeric($_POST["ilot"]))) {
    $erreur = array("Erreur", "Numéro d'ilot de type incompatible");
    echo json_encode($erreur);
    exit();
}

if (!(is_numeric($_POST["duree"]))) {
    $erreur = array("Erreur", "Duree de type incompatible");
    echo json_encode($erreur);
    exit();
}

$typeMesures = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
if (strcmp($typeMesures, "lumi") != 0 && strcmp($typeMesures, "humi") != 0
&& strcmp($typeMesures, "temp") != 0 && strcmp($typeMesures, "tous") != 0) {
    $erreur = array("Erreur", "Type de mesure incompatible");
    echo json_encode($erreur);
    exit(); 
}
// Connexion à MongoDB
$manager = new MongoDB\Driver\Manager($uri);

// Definition de la date limite selon param requête
$datestring = "-" . $_POST["duree"] . " days";
$mongodbdate = new MongoDB\BSON\UTCDateTime(strtotime($datestring) * 1000);

// Définition de la pipeline de la requete
// On récupère les valeurs voulues dans les tables voulues
$pipelinehisto = [];
// Tous les ilots
if (intval($_POST["ilot"]) == -1) {
	$pipelinehisto = [
		['$match' => [
			'idAgri' => intval($_POST["idUtilisateur"]),
			'idChamp' => intval($_POST["champ"]),
			'date' => ['$gte' => $mongodbdate]
		]],
		['$sort' => ['date' => 1]],
	];
}
else {
	$pipelinehisto = [
		['$match' => [
			'idAgri' => intval($_POST["idUtilisateur"]),
			'idChamp' => intval($_POST["champ"]),
			'idIlot' => intval($_POST["ilot"]),
			'date' => ['$gte' => $mongodbdate]
		]],
		['$sort' => ['date' => 1]],
	];
}

// Modif pipeline selon valeurs selectionnées
$commandhisto = new MongoDB\Driver\Command([
    "aggregate" => "historique",
    "pipeline" => $pipelinehisto,
    "cursor" => new stdClass(),
]);

// Execution de la requete
$cursor = $manager->executeCommand('data', $commandhisto);

$res = array();
// Remplacer valeurs nulles par N/A
foreach ($cursor as $element) {
	$restemp = [];
	$restemp[] = $element->idChamp;
	$restemp[] = $element->idIlot;
	$dbdate = $element->date->toDateTime()->
		setTimezone(new DateTimeZone("Europe/Paris"));
	$restemp[] = $dbdate->format("Y-m-d H:i:s");
	// Si toutes les mesures requises
	if (strcmp($typeMesures, "tous") == 0) {
		$restemp[] = $element->mtemp;
		$restemp[] = $element->mhumi;
		$restemp[] = $element->mlumi;
	}
	// Sinon on ajoute uniquement celle souhaitée
	else {
		$stringval = "m" . $typeMesures;
		$restemp[] = $element->$stringval;
	}
	$res[] = $restemp;
}
echo json_encode($res);
?>