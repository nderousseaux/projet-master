<?php

// Vérification des paramètres entré
if (!is_numeric($_POST["champPost"])) {
    $erreur = array("Erreur", "chamPost type non numeric");
    echo json_encode($erreur);
    exit();
}

// connection à la base MongoDB
$connection = new MongoDB\Driver\Manager("mongodb://mongo1:30001,mongo2:30002,mongo3:30003/?replicaSet=rs0");


// ===========================

// appelle fct

// retour donnée avec JSON encode avec echo
// vérifiable inspecteur d'éléments rsx



//========== FCT A APPELER ===========

//Orienter objet

//requet SQL à MongoDB
//donnée sous forme de tableau
//utilisation de pdo (wrapper)