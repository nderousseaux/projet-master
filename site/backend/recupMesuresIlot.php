<?php
// Script retournant un historique des données
use MongoDB\Driver\Manager;

// Chemin de connexion vers la base de données
$uri = getenv('MONGODB_URL');

// Vérification des paramètres d'appel
if (!(isset($_POST["idUtilisateur"])) || !(isset($_POST["numChamp"]))
|| !(isset($_POST["numIlot"])) || !(isset($_POST["typeMesures"]))) {
	$erreur = array("Erreur", "Champ(s) manquant(s) dans la requête");
	echo json_encode($erreur);
	exit();
}

if (!(is_numeric($_POST["idUtilisateur"]))) {
    $erreur = array("Erreur", "Identifiant de type incompatible");
    echo json_encde($erreur);
    exit();
}

if (!(is_numeric($_POST["numChamp"]))) {
    $erreur = array("Erreur", "Numéro de champ de type incompatible");
    echo json_encde($erreur);
    exit();
}

if (!(is_numeric($_POST["numIlot"]))) {
    $erreur = array("Erreur", "Numéro d'ilot de type incompatible");
    echo json_encde($erreur);
    exit();
}

$typeMesures = filter_input(INPUT_POST, 'typeMesures', FILTER_SANITIZE_STRING);
if (strcmp($typeMesures, "lumi") != 0 && strcmp($typeMesures, "humi") != 0
&& strcmp($typeMesures, "temp") != 0) {
    $erreur = array("Erreur", "Type de mesure incompatible");
    echo json_encde($erreur);
    exit(); 
}

// Connexion à MongoDB
$manager = new MongoDB\Driver\Manager($uri);

$datestring = "-31 days";
$mongodbdate = new MongoDB\BSON\UTCDateTime(strtotime($datestring) * 1000);
// Définition de la pipeline de la requete
// On récupère toutes les valeurs du type de mesure choisi pour l'ilot choisi
$pipelinegraph = [
	['$match' => [
		'idAgri' => intval($_POST["idUtilisateur"]),
		'idChamps' => intval($_POST["numChamp"]),
        'idIlot' => intval($_POST["numIlot"]),
        'date' => ['$gte' => $mongodbdate]
	]],
	['$sort' => ['date' => 1]],
];

// Création de la commande pour chaque collection
$commandgraph = new MongoDB\Driver\Command([
    "aggregate" => $typeMesures,
    "pipeline" => $pipelinegraph,
    "cursor" => new stdClass(),
]);

// Exécution de la commande d'agrégation
$cursor = $manager->executeCommand('data', $commandgraph);

// Traitement des données
$dates = [];
$vals = [];
foreach ($cursor as $element) {
    if (isset($element)) {
		if ($element->$typeMesures == null) {
            $vals[] = "N/A";
        }
        else {
            $vals[] = $element->$typeMesures;
        }
        $dbdate = $element->date->toDateTime()->
			setTimezone(new DateTimeZone("Europe/Paris"));
		// On garde un date pour chaque ilot
		$dates[] = $dbdate->format("Y-m-d H:i:s");
    }
}

$res = array(array(), array());
$count = count($vals);
// Renvoi du resultat
// Format : [[dates YYYY-MM-DD HH:MM:SS], [val1, val2..]]
for ($i = 0; $i < $count; $i++) {
    $res[0][] = $dates[$i];
    $res[1][] = $vals[$i];
}
echo json_encode($res);