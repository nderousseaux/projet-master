<?php

// Vérifie que le champ est présent
if (!(isset($_POST["numChamp"]))) {
	$erreur = array("Erreur", "Champ manquant dans la requête");
	echo json_encode($erreur);
	exit();
}

// Vérifie que le champ est numérique
if (!(is_numeric($_POST["numChamp"]))) {
	$erreur = array("Erreur", "Type du numéro de champ non reconnu");
	echo json_encode($erreur);
	exit();
}

/* Requête MongoDB à faire */