<?php
/**
 * Pour déconnecter un utilisateur en détruisant la session associée.
 */

if (isset($_COOKIE['idUser'])) {
    unset($_COOKIE['idUser']);
    setcookie('idUser', '', [
        'expires' => -1,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// redirection vers la page de connexion
echo "<script>location.href='../connexionCmpt.php';</script>";
die();