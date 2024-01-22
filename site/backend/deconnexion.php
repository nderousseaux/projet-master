<?php
/**
 * Pour déconnecter un utilisateur en détruisant la session associée.
 */

// session_save_path("/alloc");
// session_start();

// session_unset();
// session_destroy();
if (isset($_COOKIE['idUser'])) {
    unset($_COOKIE['idUser']); 
    setcookie('idUser', '', -1, '/'); 
}

// redirection vers la page de connexion
echo "<script>location.href='../connexionCmpt.php';</script>";
die();