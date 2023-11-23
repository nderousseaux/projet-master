<?php
/* === recupNumIlots.php === */

// Ordre 3

// Récupère les champs envoyés dans la requête
// Vérification de leur existence
if (!($_POST["numChamp"])){
    $erreur = array("Erreur", "Champ(s) manquant(s) dans la requête");
    echo json_encode($erreur);
    exit();
}
// Vérification du type de donnée entré
if (!is_numeric($_POST["numChamp"])) {
    $erreur = array("Erreur", "numChamp, n'est pas numérique");
    echo json_encode($erreur);
    exit();
}
// Vérification du type de donnée des boutons
if (!(
    $_POST["typeMesures"] === "numChamp" // A MODIF (voir BDD)
    )
) {
    $erreur = array("Erreur", "Type d'utilisateur non reconnu");
    echo json_encode($erreur);
    exit();
}


// Requête à MongoDB
// site: https://www.php.net/manual/fr/class.mongodb-driver-query.php 
$manager = new MongoDB\Driver\Manager("mongodb://mongo1:30001,mongo2:30002,mongo3:30003/data/?replicaSet=rs0");


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