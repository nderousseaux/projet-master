<?php

// Rester en local pour le debug (API ayant une limite de requêtes)
const API_LOCAL = true;

// Coordonnées à récupérer dans la base de données
const LATTITUDE = 48.52854;
const LONTITUDE = 7.711011;

if (API_LOCAL === false) {
	$cleAPI = @file_get_contents("./cleAPI.txt");

	// Si le fichier n'existe pas, renvoi une erreur
	if ($cleAPI === false) {
		$erreur = array("Erreur", "récupération clé API");
		echo json_encode($erreur);
		exit();
	}

	$reponse = @file_get_contents("https://weather.visualcrossing.com/" .
	"VisualCrossingWebServices/rest/services/timeline/". LATTITUDE . "," .
	LONTITUDE . "?unitGroup=metric&elements=datetime%2Ctempmax%2Ctempmin%2C" .
	"temp%2Chumidity%2Cprecip%2C" ."preciptype%2Cwindspeedmean%2Cwinddir%2C" .
	"cloudcover%2Cuvindex&include=days&key=" . $cleAPI . "&contentType=json");
}
else {
	$reponse = @file_get_contents("./donneesMeteoDebug.json");
}

// Renvoi l'erreur HTTP, si la requête a échoué
if ($reponse === false) {
	$erreur = array("Erreur", explode(' ', $http_response_header[0])[1]);
	echo json_encode($erreur);
	exit();
}

echo $reponse;