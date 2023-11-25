<?php

// Rester en local pour le debug (API ayant une limite de requêtes)
const MODE_LOCAL = true;

// Vérifie que les champs sont présents
if (!(
	isset($_POST["numChamp"]) &&
	isset($_POST["duree"])
)) {
	$erreur = array("Erreur", "Champ(s) manquant(s) dans la requête");
	echo json_encode($erreur);
	exit();
}

// Vérifie que le champ est numérique
if (!(is_numeric($_POST["numChamp"]))) {
	$erreur = array("Erreur", "Type du numéro de champ non reconnu");
	echo json_encode($erreur);
	exit();
}

// Récupère les champs envoyés dans la requête
if ($_POST["duree"] === "jour") {
	$duree = "today";
	$granularite = "hours";
}
else if ($_POST["duree"] === "semaine") {
	$duree = "next7days";
	$granularite = "days";
}
else {
	$erreur = array("Erreur", "Durée non reconnue");
	echo json_encode($erreur);
	exit();
}

// Coordonnées à récupérer dans la base de données
const LATITUDE = 48.52854;
const LONGITUDE = 7.711011;

/*
	Requête MongoDB à faire
	Il faut récupérer la latitude et la longitude du champ
*/

if (MODE_LOCAL === false) {
	$cleAPI = @file_get_contents("./cleAPI.txt");

	// Si le fichier n'existe pas, renvoi une erreur
	if ($cleAPI === false) {
		$erreur = array("Erreur", "récupération clé API");
		echo json_encode($erreur);
		exit();
	}

	$reponse = @file_get_contents("https://weather.visualcrossing.com/" .
	"VisualCrossingWebServices/rest/services/timeline/". LATITUDE . "," .
	LONGITUDE . '/' . $duree . "?unitGroup=metric&elements=datetime%2Ctempmax" .
	"%2Ctempmin%2Ctemp%2Chumidity%2Cprecip%2Cpreciptype%2Cwindspeedmean%2C" .
	"winddir%2Ccloudcover%2Cuvindex&include=" . $granularite .
	"&key=" . $cleAPI .	"&contentType=json");
}
else {
	if ($_POST["duree"] === "jour") {
		$reponse = @file_get_contents("./json/donneesMeteoJour.json");
	}
	else {
		$reponse = @file_get_contents("./json/donneesMeteoSemaine.json");
	}
}

// Renvoi l'erreur HTTP, si la requête a échoué
if ($reponse === false) {
	$erreur = array("Erreur", explode(' ', $http_response_header[0])[1]);
	echo json_encode($erreur);
	exit();
}

echo $reponse;