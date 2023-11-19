<?php
/* === recupMoyennes.php === */
// Ordre 5
const MODE_LOCAL = true;

// Récupère les moyennes des mesures du champ envoyés dans la requête
// Vérification de leur existence
if (!($_POST["numChamp"])) {
	$erreur = array("Erreur", "Champ(s) manquant(s) dans la requête");
	echo json_encode($erreur);
	exit();
}
// Vérification du type de donnée entré
if (!(is_numeric($_POST["numChamp"]))) {
    $erreur = array("Erreur", "Type du numéro de champ non reconnu");
	echo json_encode($erreur);
	exit();
}
// Vérification du type de donnée des boutons
if (!(
    $_POST["typeMesures"] === "moyenne"  // A MODIF (voir BDD)
    )
) {
    $erreur = array("Erreur", "Type de mesure non reconnu");
    echo json_encode($erreur);
    exit();
}

// Requête à MongoDB
// TODO