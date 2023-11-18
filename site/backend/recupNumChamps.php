<?
/* === recupNumChamps.php === */

// Ordre 2

// Récupère les champs envoyés dans la requête
// Vérification de leur existence
if (!($_POST["idUtilisateur"])) {
	$erreur = array("Erreur", "Champ(s) manquant(s) dans la requête");
	echo json_encode($erreur);
	exit();
}
// Vérification du type de donnée entré
if (!(is_numeric($_POST["idUtilisateur"]))) {
    $erreur = array("Erreur", "Type du champ ID Utilisateur, non reconnu");
	echo json_encode($erreur);
	exit();
}
// Vérification du type de donnée des boutons
if (!(
    $_POST["typeMesures"] === "idUser"  // A MODIF (voir BDD)
    )
) {
    $erreur = array("Erreur", "Type d'utilisateur non reconnu");
    echo json_encode($erreur);
    exit();
}

// Requête à MongoDB
// TODO