<?php
/* === recupNomUtilisateur.php === */

// Ordre 1

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
    $_POST["typeMesures"] === "idUser" // A MODIF (voir BDD)
    )
) {
    $erreur = array("Erreur", "Type d'utilisateur non reconnu");
    echo json_encode($erreur);
    exit();
}

echo json_encode("ok before manager");
// Requête à MongoDB
$manager = new MongoDB\Driver\Manager("mongodb://localhost:30001/?replicaSet=rs0");
var_dump($manager);
echo json_encode("ok after manager");
$command = new MongoDB\Driver\Command(['ping' => 1]);
echo json_encode("OK après manager/command");


try {
    $cursor = $manager->executeCommand('admin', $command);
} catch(MongoDB\Driver\Exception $e) {
    echo $e->getMessage(), "\n";
    exit;
}

/* The ping command returns a single result document, so we need to access the
 * first result in the cursor. */
$response = $cursor->toArray()[0];

var_dump($response);



// ===========================

// appelle fct
// - connection à la bdd
// - faire la requête etc..

// retour donnée avec JSON encode avec echo
// vérifiable inspecteur d'éléments rsx



//========== FCT A APPELER ===========

//Orienter objet

//requet SQL à MongoDB
//donnée sous forme de tableau
//utilisation de pdo (wrapper)
?>