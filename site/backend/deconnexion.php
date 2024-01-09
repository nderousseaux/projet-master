<?php
/**
 * Pour déconnecter un tilisateur en détruisant la session associée.
 */

session_start();

session_unset();
session_destroy();

// redirection vers la page de connexion
echo "<script>location.href='../connexionCmpt.php';</script>";
die();