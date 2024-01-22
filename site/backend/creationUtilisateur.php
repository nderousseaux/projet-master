<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

define('OK', 0);
define('ERROR', 1);

define('LIEN', "localhost:8080");   // adresse du site pour test local
//define('LIEN', ""); // vraie adresse

/**
 * Genere une chaine de caractere aleatoire
 * @param length la longueur de la chaine
 * @param keyspace l'alphabet pour construire la chaine
 */
function random_str ($length, $keyspace) {
    $str = '';
    $max = strlen($keyspace) - 1;

    if ($max < 1) {
        throw new Exception('$keyspace doit contenir au moins 2 caracteres');
    }

    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }

    return $str;
}


/**
 * Genere un mot de passe temporaire aleatoire qui repond au criteres suivants:
 * longueur 20; au moins 1 majuscule, minuscule, chiffre et caractere special
 */
function generate_password() {
    $lowercase      = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers        = '0123456789';
    $specialChars   = '!@#$%^&*()_-+=<>?';
    $combinedChars = $lowercase . $uppercase . $numbers . $specialChars;

    // genere le gros du mdp sans garantie de type de caractere
    $password = random_str(16, $combinedChars);

    // ajout d'un caractere de chaque type
    $charsArray = [$lowercase, $uppercase, $numbers, $specialChars];
    foreach($charsArray as $type) {
        $password .= random_str(1, $type);
    }

    // melange les caracteres avant de retourner la chaine
    return str_shuffle($password);
}


// envoie un email pour informer l'utilisateur de la création d'un compte à son nom
// et lui fournir son mot de passe initial
function notify($nom, $prenom, $dst_mail, $mdp) {
    $lien = LIEN;
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer();

    // récupération des identifiants du serveur de mail
    $env = parse_ini_file('/.env');
    $smtpId = $env["SMTP_ID"];
    $smtpPw = $env["SMTP_PW"];
    file_put_contents("/var/www/html/mail_co", $smtpId." ".$smtpPw);

    try {
        //Server settings
        $mail->SMTPDebug  = 2;                  //Enable verbose debug output
        $mail->isSMTP();                        //Send using SMTP
        $mail->Host       = 'smtp.orange.fr';   //Set the SMTP server to send through
        $mail->SMTPAuth   = true;               //Enable SMTP authentication
        $mail->Username   = $smtpId;            //SMTP username
        $mail->Password   = $smtpPw;            //SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($smtpId, 'Notification Alsagrinet');
        $mail->addAddress($dst_mail, "$prenom $nom");       //Add a recipient

        //Content
        $mail->isHTML(true);                                //Set email format to HTML
        $mail->Subject = 'Nouveau Compte Alsagrinet';
        $mail->Body    = "Bonjour $prenom $nom, <br/>Voici votre mot de passe temporaire: $mdp<br/>Vous pouvez vous connecter ici: $lien";
        // pour client mail qui ne supporterait pas html
        $mail->AltBody = "Bonjour $prenom $nom, Voici votre mot de passe temporaire: $mdp. Vous pouvez vous connecter ici: $lien";

        $mail->Debugoutput = function($str, $level) {file_put_contents("/var/www/html/mail_".$level."_erreur.log", $str);};

        $mail->send();
        return OK;
    } catch (Exception $e) {
        file_put_contents("/var/www/html/mail_erreur.log", $mail->ErrorInfo);
        return ERROR;
    }
}


// Requete pour récupérer le plus grand idUser pour trouver le 1er libre
function firstFreeIdUser($mongoClient, $database, $collection) {
    $filter = [];
    $sort = ['idUser' => -1];   // tri par ordre décroissant
    $options = ['sort' => $sort, 'limit' => 1];  // on veut 1 seul elt (le plus grand)

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $mongoClient->executeQuery("$database.$collection", $query);
    $highestUser = current($cursor->toArray());
    $nextIdUser = $highestUser ? $highestUser->idUser + 1 : 0;

    return $nextIdUser;
}


// Vérifie que toutes les infos sont présentes
if (!(isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["role"]) && isset($_POST["courriel"]))) {
	$erreur = array("Erreur", "Informations manquantes dans la requête");
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
$uri = getenv('MONGODB_URL');

// Créer le client
try {
    $mongoClient = new MongoDB\Driver\Manager($uri);
} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
    die("Failed to connect to MongoDB: " . $e->getMessage());
}


// Récupérer IdAgri de l'utilisateur actuel pour ajouter nouveau user au même agri
session_start();

$mail = $_POST["courriel"];
$mdp = generate_password();
$newCompte = [
    "idUser"    => firstFreeIdUser($mongoClient, $database, $collection),
    "idAgri"    => $_COOKIE["idAgri"],
    "role"      => $_POST['role'],
    "nom"       => $_POST['nom'],
    "prenom"    => $_POST['prenom'],
    "mail"      => $mail,
    "mdp"       => password_hash($mdp, PASSWORD_DEFAULT),
    "mdp_temp"  => true,
];

// vérifier qu'il n'y a pas déjà un compte pour cette @ mail
// Défini le filtre
$filtre = [
	"mail" => $mail
];

// Créé la requête
$requete = new MongoDB\Driver\Query($filtre);

$cursor = $mongoClient->executeQuery("$database.$collection", $requete);

if (!$cursor->isDead()) { // mail deja existant
    echo json_encode([ERROR, "Il existe déjà un utilisateur avec cette adresse courriel '$mail'"]);
    exit();
}

// Ajouter le nouvel utilisateur
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);

$insert = new MongoDB\Driver\BulkWrite();
$insert->insert($newCompte);

try {
    $err = OK;
    $result = $mongoClient->executeBulkWrite("$database.$collection", $insert, $writeConcern);
    $err = notify($_POST['nom'], $_POST['prenom'], $mail, $mdp);
    //file_put_contents("/var/www/html/mail_erreur2.log", json_encode([OK, "$mdp"]));
    echo json_encode([0, "$mdp"]);
} catch (MongoDB\Driver\Exception\BulkWriteException $e) {
    echo json_encode([ERROR, "Erreur lors de la création du compte."]);
    die("Error inserting document: " . $e->getMessage());
}
