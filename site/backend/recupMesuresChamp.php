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
