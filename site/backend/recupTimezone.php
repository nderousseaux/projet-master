<?php
// Récupère la timezone du champ en fonction de sa localisation
// Doit être appelé par un script ayant une connexion existante à la bdd

// Check si la connexion à la bdd existe 
if (!(isset($manager))) {
	$erreur = array("Erreur", "Connexion bdd inexistante");
	echo json_encode($erreur);
	exit();
}

// Récupération du numéro du champ courant via la variable POST
$numchamp = intval($_POST["numChamp"]);

// Récupération des coordonnées du champ dans la bdd