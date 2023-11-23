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

// Debug contenu du param d'entré
echo "debug1\n";
$var = $_POST["numChamp"];
echo "numchamps $var";

// Requête à MongoDB
// site: https://www.php.net/manual/fr/class.mongodb-driver-query.php 

$mongoClient = new MongoDB\Client("mongodb://localhost:3001");
echo "debug2\n";
//$database = $mongoClient->selectDatabase("votre_base_de_donnees");
$database = $mongoClient->selectDatabase($mongoClient);
$collection = $database->selectCollection("agriculteur");


// Exécution de la requête
$cursor = $collection->find([], ['projection' => ['champs.ilots' => 1]]);

echo "debug3\n";

// Parcours des résultats
foreach ($cursor as $document) {
    // Traitement des résultats
    var_dump($document);
}

echo "debug4\n";

echo $tbl;
echo json_encode($tbl);