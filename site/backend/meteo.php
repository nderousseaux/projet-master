<?php

// Récupère la durée et la granularité souhaitée
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

// Rester en local pour le debug (API ayant une limite de requêtes)
const MODE_LOCAL = true;

// Coordonnées à récupérer dans la base de données
const LATTITUDE = 48.52854;
const LONTITUDE = 7.711011;

if (MODE_LOCAL === false) {
	$cleAPI = @file_get_contents("./cleAPI.txt");

	// Si le fichier n'existe pas, renvoi une erreur
	if ($cleAPI === false) {
		$erreur = array("Erreur", "récupération clé API");
		echo json_encode($erreur);
		exit();
	}

	$reponse = @file_get_contents("https://weather.visualcrossing.com/" .
	"VisualCrossingWebServices/rest/services/timeline/". LATTITUDE . "," .
	LONTITUDE . '/' . $duree . "?unitGroup=metric&elements=datetime%2Ctempmax" .
	"%2Ctempmin%2Ctemp%2Chumidity%2Cprecip%2Cpreciptype%2Cwindspeedmean%2C" .
	"winddir%2Ccloudcover%2Cuvindex&include=" . $granularite .
	"&key=" . $cleAPI .	"&contentType=json");
}
else {
	if ($_POST["duree"] === "jour") {
		$reponse = @file_get_contents("./donneesMeteoJour.json");
	}
	else {
		$reponse = @file_get_contents("./donneesMeteoSemaine.json");
	}
}

// Renvoi l'erreur HTTP, si la requête a échoué
if ($reponse === false) {
	$erreur = array("Erreur", explode(' ', $http_response_header[0])[1]);
	echo json_encode($erreur);
	exit();
}

echo $reponse;