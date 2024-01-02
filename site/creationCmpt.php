<!DOCTYPE html>
<html lang="fr">
<head>
	<?php include "backend/checkConnexion.php"?>
	<?php include "assets/head.php"?>	
	<title>Création de compte</title>
	<meta name="description" content="Création de compte"/>
	<link rel="stylesheet" type="text/css" href="style/gestionCmpt.css"/>
	<link rel="stylesheet" type="text/css" href="style/accesCmpt.css"/>
</head>
<body>
<header>
	<?php include "assets/logo.php"?>
</header>
<div id="corps">
	<section id="infosCmpt" class="containerSecVerti">
		<h1>Création de compte</h1>
		<form id="formCmpt">
			<label class="colonne" for="prenom">Prénom</label>
			<input class="colonne" id="prenom" name="prenom"
				placeholder="Prénom"></input>
			<label class="colonne" for="nom">Nom</label>
			<input class="colonne" id="nom" name="nom" placeholder="Nom">
			</input>
			<label class="role" for="role">Rôle</label>
			<select name="role" id="role" required>
				<option value="standard">Standard</option>
				<option value="admin">Administrateur</option>
			</select>
			<label class="colonne" for="courriel">Courriel</label>
			<input class="colonne" id="courriel" name="courriel"
				placeholder="adresse@courriel.com"></input>
			<button type="button" id="enreg">Créer</button>
		</form>
	</section>
</div>
<?php include "assets/footer.php"?>
<script type="text/javascript" src="scripts/recupDonnees.js"></script>
<script type="text/javascript" src="scripts/verificationsInput.js"></script>
<script type="text/javascript" src="scripts/gestionCmpt.js"></script>
<script src="scripts/entete.js"></script>
<script>
	/*** Gestion des données ***/
		// Gère les champs du formulaire et l'envoi des données
	document.getElementById("enreg").addEventListener("click",
	e => {
		creationCmpt(e);
	});
	document.querySelector("form").addEventListener("submit", e => {
		creationCmpt(e);
	});


	/*** Changements du DOM ***/
		// Gère le défilement vers le haut de la page
	activerDefilementHautPage();

		// Gère la taille du header en fonction du défilement
	activerHeaderReduit();
</script>
</body>
</html>