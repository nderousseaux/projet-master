<!DOCTYPE html>
<html lang="fr">
<head>
	<?php include "snippets/head.php"?>	
	<title>Création de compte</title>
	<meta name="description" content="Création de compte"/>
	<link rel="stylesheet" type="text/css" href="style/connexionCmpt.css"/>
	<link rel="stylesheet" type="text/css" href="style/creationCmpt.css"/>
</head>
<body>
<header>
	<?php include "snippets/logo.php"?>
</header>
<div id="corps">
	<section id="secForm">
		<h1>Création de compte</h1>
		<form action="backend/creationUtilisateur.php" method="post">
			<input type="text" name="prenom" placeholder="Prénom" required/>
			<input type="text" name="nom" placeholder="Nom" required/>
			<input type="text" name="role" placeholder="Droits" required/> <!-- standard ou admin -->

			<!-- make nice then put inplace of line above
			<label for="role">role:</label>
			<select name="role" id="role" required>
			  <option value="standard">Standard</option>
			  <option value="admin">Administrateur</option>
			</select> -->

			<input type="text" name="courriel" placeholder="Courriel" required/>
			<!--<input type="password" name="mdp" placeholder="Mot de passe" required/> Ne fait pas de sens ici-->
			<input type="submit" name="creer" value="Créer"/>
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