<?php
include_once "classe/BaseDeDonnees.php";
$bdd = new BaseDeDonnees("./bdd/donnees.db");

$bdd->initialiserTable();

// Si les données sont présentes, les insérer dans la base de données
// En cas d'erreur, renvoie 1, sinon 0
if (
	isset($_POST["nom"]) &&
	isset($_POST["prenom"]) &&
	isset($_POST["courriel"]) &&
	isset($_POST["message"])
) {
	$retour = $bdd->insererMessage(
		htmlspecialchars($_POST["nom"], ENT_QUOTES, "UTF-8"),
		htmlspecialchars($_POST["prenom"], ENT_QUOTES, "UTF-8"),
		htmlspecialchars($_POST["courriel"], ENT_QUOTES, "UTF-8"),
		htmlspecialchars($_POST["message"], ENT_QUOTES, "UTF-8")
	);

	// En cas d'erreur, afficher l'erreur
	if ($retour !== null) {
		echo 2;
		die();
	}
	echo 0;
}
else {
	echo 1;
}