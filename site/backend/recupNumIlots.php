<?php
/* === recupNumIlots.php === */
// Récupère les champs envoyés dans la requête
if (!($_POST["numChamp"])){
	$erreur = array("Erreur", "Champ manquant dans la requête");
	echo json_encode($erreur);
	exit();
}
if (!is_numeric($_POST["numChamp"])) {
	$erreur = array("Erreur", "numChamp n'est pas un nombre");
	echo json_encode($erreur);
	exit();
}

// Connexion à MongoDB
use MongoDB\Driver\Manager;
$uri = "mongodb://localhost:30001";

// Créé le client
$client = new MongoDB\Driver\Manager($uri);

// Défini la requête pour récupérer "ilots" dans "champs"
$requete = new MongoDB\Driver\Query([],
	["projection" => ["champs.ilots" => 1]]);

// Exécute la requête
$resultat = $client->executeQuery("data.agriculteur", $requete);

// Converti le résultat en tableau
$donnees = iterator_to_array($resultat);

// Récupère uniquement le nombre d'ilots, du champ demandé
$donnees = $donnees[0]->champs->ilots;

// Renvoi le résultat
echo json_encode($donnees);