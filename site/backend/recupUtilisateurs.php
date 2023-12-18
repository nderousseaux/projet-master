<?php

$fichierDonneesUtilisateurs = "json/utilisateurs.json";
if (file_exists($fichierDonneesUtilisateurs)) {
	echo file_get_contents($fichierDonneesUtilisateurs);
}
else {
	$erreur = array("Erreur", "Le fichier n'existe pas");
	echo json_encode($erreur);
	exit();
}