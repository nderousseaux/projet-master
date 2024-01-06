<?php
// Script de chargement de la page principale du frontend 
// Effectue la vérification des paramètres et l'initialisation 
// de la connexion vers la base de données MongoDB

// Chemin de connexion vers la base de données
$uri = "mongodb://localhost:30001";


// Vérification des paramètres d'appel
if (!(isset($_POST["idUtilisateur"]))) {
	$erreur = array("Erreur", "Identifiant manquant dans la requête");
	echo json_encode($erreur);
	exit();
}

if (!(is_numeric($_POST["idUtilisateur"]))) {
    $erreur = array("Erreur", "Identifiant de type incompatible");
    echo json_encde($erreur);
    exit();
}

$firstload = true;
// Si numéro champ présent, on sait que l'utilisateur a sélectionné un champ
if (isset($_POST["numChamp"])) {
    if (!(is_numeric($_POST["numChamp"]))) {
        $erreur = array("Erreur", "Numéro de champ de type incompatible");
        echo json_encode($erreur);
        exit();
    }
    $firstload = false;
}

// Connexion à MongoDB
use MongoDB\Driver\Manager;
$manager = new MongoDB\Driver\Manager($uri);

// Récupération des données
$data = array();
// Si premier chargement, on envoie le nom d'utilisateur également
if ($firstload) {
    ob_start();
    include "recupNomUtilisateur.php";
    $data[] = ob_get_contents();
    ob_end_clean();

    // On affecte la valeur numChamp pour les prochains scripts
    $_POST["numChamp"] = 0;
}

// Ajout des infos champs, moyennes, mesures et graphique ilot dans un tableau
ob_start();
include "recupInfosChamp.php";
$data[] = ob_get_contents();
ob_end_clean();

ob_start();
include "recupMoyennes.php";
$data[] = ob_get_contents();
ob_end_clean();

ob_start();
include "recupMesuresChamp.php";
$data[] = ob_get_contents();
ob_end_clean();

ob_start();
include "recupMesuresIlot.php";
$data[] = ob_get_contents();
ob_end_clean();

// Retour des données pour affichage de la page principale
return $data;
?>