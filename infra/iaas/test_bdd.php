<?php
$mongo = new MongoDB\Client('mongodb://mongo1:30001');
//Si pas de connexion, erreur
$dbs = $mongo->listDatabases();
echo json_encode("connection ok");
?>
