<!DOCTYPE html>
<html lang="fr">
<head>
	<?php include "snippets/head.php"?>
	<title>Connexion utilisateur</title>
	<meta name="description" content="Connexion utilisateur"/>
	<link rel="stylesheet" type="text/css" href="style/connexionCmpt.css"/>
</head>
<body>
<header>
	<?php include "snippets/logo.php"?>
</header>
<div id="corps">
	<section id="secForm">
		<h1>Connexion</h1>
		<form action="connexionUtilisateur.php" method="post">
			<input type="text" name="courriel" placeholder="Courriel" required/>
			<input type="password" name="mdp" placeholder="Mot de passe" required/>
			<input type="submit" value="Connexion"/>
		</form>
	</section>
</div>
<?php include "snippets/footer.php"?>
<script src="scripts/entete.js"></script>
<script>
	/*** Changements du DOM ***/
		// Gère le défilement vers le haut de la page
	activerDefilementHautPage();

		// Gère la taille du header en fonction du défilement
	activerHeaderReduit();
</script>
</body>
</html>