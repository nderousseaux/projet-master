<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<title>Gestion du compte</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#fbfbfc" media="(prefers-color-scheme: light)"/>
	<meta name="theme-color" content="#080809" media="(prefers-color-scheme: dark)"/>
	<link rel="icon" type="image/webp" href="img/favicon.webp"/>
	<meta name="description" content="Gestion du compte"/>
	<link rel="stylesheet" type="text/css" href="style/index.css"/>
	<link rel="stylesheet" type="text/css" href="style/gestionCmpt.css"/>
</head>
<body>
<header>
	<?php include "snippets/logo.php"?>
	<section>
		<p>-</p>
		<div id="ddCmpt" class="dropdown">
			<button class="dropbtn">⇩</button>
			<div id="selectCmpt" class="dropdownContent ddHeader">
				<button value="deco">Déconnexion</button>
			</div>
		</div>
	</section>
</header>
<div id="corps">
	<section id="secCmpt">
		<div id="infosCmpt">
			<label class="colonne" for="prenom">Prénom</label>
			<input class="colonne" name="prenom" placeholder="John"></input>
			<label class="colonne" for="nom">Nom</label>
			<input class="colonne" name="nom" placeholder="Doe"></input>
			<label class="colonne" for="courriel">Courriel</label>
			<input class="colonne" name="courriel" placeholder="abc@xyz.com"></input>
			<label class="colonne" for="mdp">Mot de passe</label>
			<input type="password" name="mdp" class="colonne" placeholder="******"></input>
		</div>
		<div id="icone">
			<div>J. D.</div>
		</div>
	</section>
</div>
<div id="confirmChgmt">
	<dialog>
		<h1>Voulez-vous enregistrer la modification ?</h1>
		<form method="dialog">
			<div id="msg">
			</div>
			<div>
				<button id="annuler">Annuler</button>
				<button id="confirmer">Confirmer</button>
			</div>
		</form>
</div>
</dialog>
<?php include "snippets/footer.php"?>
<script type="text/javascript" src="scripts/recupDonnees.js"></script>
<script type="text/javascript" src="scripts/afficherDonnees.js"></script>
<script type="text/javascript" src="scripts/gestionCmpt.js"></script>
<script src="scripts/entete.js"></script>
<script>
	// Gère les vérifications des champs du formulaire
	gestionInputCmpt();

	/*** Affichage des données ***/
		// Récupérer l'ID utilisateur (à gérer par l'équipe gestion de compte)
	let idUtilisateur = 0;

		// Affiche le nom de l'utilisateur dans le header
	afficherNomUtilisateur(idUtilisateur);

	/** Changements du DOM **/
		// Gère le défilement vers le haut de la page
	activerDefilementHautPage();

		// Gère la taille du header en fonction du défilement
	activerHeaderReduit();
</script>
</body>
</html>