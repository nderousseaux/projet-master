<?php

use MongoDB\Driver\Manager;

// Vérifie que le champ est présent
if (!(isset($_POST["idUtilisateur"]))) {
	$erreur = array("Erreur", "Champ manquant dans la requête");
	echo json_encode($erreur);
	exit();
}

// Vérifie que le champ est numérique
if (!(is_numeric($_POST["idUtilisateur"]))) {
	$erreur = array("Erreur", "Type du numéro d'utilisateur non reconnu");
	echo json_encode($erreur);
	exit();
}

// Connexion à MongoDB
$uri = "mongodb://localhost:30001";

// Créé le client
$client = new MongoDB\Driver\Manager($uri);

// Défini le filtre
$filtre = ["idAgri" => intval($_POST["idUtilisateur"])];

// Créé la requête
$requete = new MongoDB\Driver\Query($filtre);

// Exécute la requête et récupère le résultat
$resultat = $client->executeQuery("data.agriculteur", $requete);

// Traite les données
foreach ($resultat as $element) {
	// Accède à la propriété "nomAgri"
	$resultat = $element->nomAgri;
}

// Renvoi le nom d'utilisateur
echo json_encode($resultat);