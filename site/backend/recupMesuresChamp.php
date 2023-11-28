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

// Connexion à MongoDB
use MongoDB\Driver\Manager;
$uri = "mongodb://localhost:30001";

// Créé le client
$client = new MongoDB\Driver\Manager($uri);

// Défini le filtre
$filtre = [
    "idAgri" => intval($_POST["idUtilisateur"]),
    "idChamps" => intval($_POST["numChamp"]),
];

// Défini les projections
$optionsilots = ["projection" => ["champs.ilots" => 1]];
$options = ["projections" => ["dates" => 1]];

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
