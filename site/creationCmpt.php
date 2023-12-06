<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<title>Création de compte</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#fbfbfc" media="(prefers-color-scheme: light)"/>
	<meta name="theme-color" content="#080809" media="(prefers-color-scheme: dark)"/>
	<link rel="icon" type="image/webp" href="img/favicon.webp"/>
	<meta name="description" content="Création de compte"/>
	<link rel="stylesheet" type="text/css" href="style/index.css"/>
	<link rel="stylesheet" type="text/css" href="style/connexionCmpt.css"/>
	<link rel="stylesheet" type="text/css" href="style/creationCmpt.css"/>
</head>
<body>
<header>
	<?php include "snippets/logo.php"?>
</header>
<div id="corps">
	<section id="secForm">
		<h1>Connexion</h1>
		<form action="connexionUtilisateur.php" method="post">
			<input type="text" name="prenom" placeholder="Prénom" required/>
			<input type="text" name="nom" placeholder="Nom" required/>
			<input type="text" name="courriel" placeholder="Courriel" required/>
			<input type="password" name="mdp" placeholder="Mot de passe" required/>
			<input type="submit" value="Création"/>
		</form>
	</section>
</div>
<?php include "snippets/footer.php"?>
<script type="text/javascript" src="scripts/gestionCmpt.js"></script>
<script src="scripts/entete.js"></script>
<script>
	// Gère les champs du formulaire
	creationCmpt();

	/*** Changements du DOM ***/
		// Gère le défilement vers le haut de la page
	activerDefilementHautPage();

		// Gère la taille du header en fonction du défilement
	activerHeaderReduit();
</script>
</body>
</html>