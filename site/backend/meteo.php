<?php

const API_LOCAL = true;

if (API_LOCAL === false) {
	$cleAPI = @file_get_contents("./cleAPI.txt");

	// Si le fichier n'existe pas, renvoie une erreur
	if ($cleAPI === false) {
		$erreur = array("Erreur", "récupération clé API");
		echo json_encode($erreur);
		exit();
	}

	$reponse = @file_get_contents("https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/48.52854%2C7.711011?unitGroup=metric&elements=datetime%2Ctempmax%2Ctempmin%2Ctemp%2Chumidity%2Cprecip%2Cpreciptype%2Cwindspeedmean%2Cwinddir%2Ccloudcover%2Cuvindex&include=days&key=" . $cleAPI . "&contentType=json");
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