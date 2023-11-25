<?php

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

/* Requête MongoDB à faire */