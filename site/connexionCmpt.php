<!DOCTYPE html>
<html lang="fr">
<head>
	<?php include "assets/head.php"?>
	<title>Connexion au compte</title>
	<meta name="description" content="Connexion au compte"/>
	<link rel="stylesheet" type="text/css" href="style/gestionCmpt.css"/>
	<link rel="stylesheet" type="text/css" href="style/accesCmpt.css"/>
</head>
<body>
<header>
	<?php include "assets/logo.php"?>
</header>
<div id="corps">
	<section id="infosCmpt" class="containerSecVerti">
		<h1>Connexion</h1>
		<form id="formCmpt">
			<label class="colonne" for="courriel">Courriel</label>
			<input class="colonne" id="courriel" name="courriel"
				placeholder="adresse@courriel.com"></input>
			<label class="colonne" for="mdp">Mot de passe</label>
			<input type="password" id="mdp" name="mdp"
				class="colonne" placeholder="******"></input>
			<button id="enreg">Connexion</button>
		</form>
	</section>
</div>
<?php include "assets/footer.php"?>
<script type="text/javascript" src="scripts/recupDonnees.js"></script>
<script type="text/javascript" src="scripts/gestionCmpt.js"></script>
<script src="scripts/entete.js"></script>
<script>
	/*** Gestion des données ***/
		// Gère les champs du formulaire et l'envoi des données
	document.getElementById("enreg").addEventListener("click",
	e => {
		connexionCmpt(e);
	});
	document.querySelector("form").addEventListener("submit", e => {
		connexionCmpt(e);
	});


	/*** Changements du DOM ***/
		// Gère le défilement vers le haut de la page
	activerDefilementHautPage();

		// Gère la taille du header en fonction du défilement
	activerHeaderReduit();
</script>
</body>
</html>