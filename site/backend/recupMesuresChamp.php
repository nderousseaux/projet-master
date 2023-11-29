<?php

// Récupère les informations du champ pour le tableau
// Vérification de leur existence
if (!(isset($_POST["idUtilisateur"]))) {
	$erreur = array("Erreur", "Champ manquant dans la requête");
	echo json_encode($erreur);
	exit();
}

// Vérifie que les entrées sont de type numérique
if (!(is_numeric($_POST["numChamp"]))) {
    $erreur = array("Erreur", "Type du numéro de champ non reconnu");
	echo json_encode($erreur);
	exit();
}

// Connexion à MongoDB
use MongoDB\Driver\Manager;
$uri = "mongodb://localhost:30001";

// Créé le client
$client = new MongoDB\Driver\Manager($uri);

//Requêtes : 
// 1. Récupérer toutes les données pour chaque ilot du champ
// 2. Conserver date la plus récente
// 3. Formater sous forme de tableau de tableau:
// [numchamp, numilot(s), date_dernier_val,(moy)temp, (moy)humi, (moy)lumi]

// Défini le filtre
$filtre = [
    "idAgri" => intval($_POST["idUtilisateur"]),
	"idChamps" => intval($_POST["idChamp"]),
];

// Défini les projections
// Sort par numéro d'ilot ?
$options = ["projection" => ["dates" => 1, "valeurs" => 1]];

// Créé la requête
$requete = new MongoDB\Driver\Query($filtre, $options);
$curdate = new DateTime("UTC");

<<<<<<< HEAD
/* Requête MongoDB à faire */

// >> Code bouchon à supprimer
$fichierDonnees = "./json/donneesTableauTtesDonnees.json";
if (file_exists($fichierDonnees)) {
	$reponse = @file_get_contents($fichierDonnees);
	echo $reponse;
}
else {
	$erreur = array("Erreur", "Fichier non trouvé");
	echo json_encode($erreur);
	exit();
}
=======
// Exécute la requête pour chaque type de capteur et récupère le résultat
$resultattemp = $client->executeQuery("data.temp", $requete);
$resultathumi = $client->executeQuery("data.humi", $requete);
$resultatlumi = $client->executeQuery("data.lumi", $requete);

// Traitement des données
$counttemp = 0;
$counthumi = 0;
$countlumi = 0;
$moytemp = 0;
$moyhumi = 0;
$moylumi = 0;
$lastdtemp = null;
$lastdhumi = null;
$lastdlumi = null;


$hasdeadsensor = false;
foreach ($resultattemp as $element) {
	if (isset($element)) {
		foreach($element->dates as $dbdate) {
			// Date sous format millisecondes depuis l'epoch
			$dtdate = $dbdate->toDateTime();
			$gap = $curdate->diff($dtdate);

			// Si dernier valeur du capteur date de plus de 30 minutes, ko
			if ($gap->format("%i") > 30) {
				$hasdeadsensor = true;
			}
			else {
				$counttemp = $counttemp + 1;
				// On stocke la date de la derniere mesure
				if ($lastdtemp == null || $lastdtemp < $dtdate) 
					$lastdtemp = $dtdate;
			}
		}
		foreach ($element->valeurs as $mesure) {
			$moytemp += floatval($mesure);
			
		}
	}
	//Si pas de retour de la bdd, erreur
	else {
		$erreur = array("Erreur", "Retour vide de la bdd");
		echo json_encode($erreur);
		exit();
	}
}

// Création des tableaux
$res = array();

>>>>>>> 5165336... avancée requete mesure champ, reflexion methode
