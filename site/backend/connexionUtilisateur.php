<?php

// fonction de hashage du mot de passe (pour comparaison avec celui stocké en bdd)
function hash_mdp($mdp) {
    //TODO
    return $mdp;
}

// Vérifie que toutes les infos sont présentes
if (!(isset($_POST["courriel"]) && isset($_POST["mdp"]))) {
	$erreur = array("Erreur", "Infos manquantes dans la requête");
	echo json_encode($erreur);
	exit();
}

// Infos de connexion à la BDD
$hosts = [
    "mongo1:30001",
    "mongo2:30002",
    "mongo3:30003"
];
$connectionString = implode(",", $hosts);

$database       = "data";
$collection     = "compte";
$replicaSetName = "rs0";

// uri de connnexion
use MongoDB\Driver\Manager;
//$uri = "mongodb://$connectionString/?replicaSet=$replicaSetName";
$uri = "mongodb://localhost:30001";

// Créer le client
try {
    $mongoClient = new MongoDB\Driver\Manager($uri);
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
    die("Failed to connect to MongoDB: " . $e->getMessage());
}

// Défini le filtre
$filtre = [
	"mail" => $_POST["courriel"]
];
//$options = [];

// Créé la requête
$requete = new MongoDB\Driver\Query($filtre);
$curdate = new DateTime("UTC");

$cursor = $mongoClient->executeQuery("$database.$collection", $requete);

if (empty($cursor)) { // mail non existant
    echo "Email erroné, veuillez réessayer";
    echo "<script>location.href='../connexionCmpt.php';</script>";
    die();
    //http_redirect($url);
}

foreach ($cursor as $infosUser) {
    $idUser = $infosUser->idUser;
    $idAgri = $infosUser->idAgri;
    $role = $infosUser->role;
    $nom = $infosUser->nom;
    $prenom = $infosUser->prenom;
    $mail = $infosUser->mail;
    $mdp = $infosUser->mdp;
}

if ($mdp == "pasSafeDuTout" && $_POST["mdp"] == "pasSafeDuTout") {  // nouveau compte
    echo "Bienvenue, veuillez définir votre mot de passe.";
    // TODO: formulaire de saisie nouveau mdp
} else if (hash_mdp($_POST["mdp"]) == $mdp) {   // mdp valide
    //echo "Connexion réussie!";
    echo "<script>location.href='../index.php';</script>";
    die();
    //TODO: rediriger sur index.php
} else {    // mauvais mdp
    //echo "Mot de passe erroné, veuillez réessayer";
    echo "<script>location.href='../connexionCmpt.php';</script>";
    die();
    //http_redirect($url);
}


?>