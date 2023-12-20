<?php
	// Récupérer ici le rôle de l'utilisateur
	$role = "admin"; // "admin" ou "standard"
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<?php include "assets/head.php"?>
	<title>Gestion du compte</title>
	<meta name="description" content="Gestion du compte"/>
	<link rel="stylesheet" type="text/css" href="style/gestionCmpt.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
		integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
		crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body tabindex='0'>
<header>
	<?php include "assets/logo.php"?>
	<section title="Retourner en haut de la page">
		<p title=''>-</p>
		<div id="ddCmpt" class="dropdown" title=''>
			<button class="dropbtn">⇩</button>
			<div id="selectCmpt" class="dropdownContent ddHeader">
				<a href=".">Accueil</a>
				<button value="deco">Déconnexion</button>
			</div>
		</div>
	</section>
</header>
<div id="corps">
	<section id="secCmpt">
		<div id="infosCmpt" class="containerSecVerti">
			<h1>Modifier les informations</h1>
			<?php if ($role === "admin")
				include "assets/selecteurUtilisateur.php"
			?>
			<form id="formCmpt">
				<label class="colonne" for="idAgri">Identifiant agriculteur
				</label>
				<div id="secCopie">
					<input class="colonne" id="idAgri" disabled name="idAgri"
						placeholder='-'>
					</input>
					<div>
						<button
							onclick="copierPressePapier()"
							onmouseout="reinitBouton()"
						>
							<i class="fa fa-copy"></i>
							<span class="bulle" id="texteBouton">
								Copier dans le presse-papier
							</span>
						</button>
					</div>
				</div>
				<label class="colonne" for="prenom">Prénom</label>
				<input class="colonne" id="prenom" name="prenom"
					placeholder="Prénom" value='-'></input>
				<label class="colonne" for="nom">Nom</label>
				<input class="colonne" id="nom" name="nom"
					placeholder="Nom" value='-'></input>
				<?php if ($role === "admin")
					include "assets/selecteurRole.php"
				?>
				<label class="colonne" for="courriel">Courriel</label>
				<input class="colonne" id="courriel" name="courriel"
					placeholder="adresse@courriel.com" value='-'></input>
				<label class="colonne" for="mdp">Mot de passe</label>
				<input type="password" id="mdp" name="mdp"
					class="colonne" placeholder="******" value=''></input>
				<label class="colonne" for="couleur1">Couleur 1</label>
				<input class="colonne selectCouleur" id="couleur1"
					name="couleur1" type="color" placeholder="#000000"
					value="#000000"></input>
				<label class="colonne" for="couleur2">Couleur 2</label>
				<input class="colonne selectCouleur" id="couleur2"
					name="couleur2" type="color" placeholder="#ffffff"
					value="#ffffff"></input>
				<button id="reinit">Réinitialiser</button>
				<button id="enreg">Enregistrer</button>
			</form>
		</div>
		<div id="icone">
			<div>-</div>
		</div>
	</section>
</div>
<?php include "assets/footer.php"?>
<script type="text/javascript" src="scripts/recupDonnees.js"></script>
<script type="text/javascript" src="scripts/afficherDonnees.js"></script>
<script type="text/javascript" src="scripts/verificationsInput.js"></script>
<script type="text/javascript" src="scripts/gestionCmpt.js"></script>
<?php if ($role === "admin")echo '<script type="text/javascript"
	src="scripts/interactionsBtn.js"></script>'?>
<script src="scripts/entete.js"></script>
<script>
	let idUtilisateur = 0;


	/*** Gestion des données ***/
		// Récupère les données de l'utilisateur et rempli le formulaire avec
	afficherDonneesUtilisateur(idUtilisateur<?php
		if ($role === "admin") echo ", true";?>);

		// Gère les vérifications des champs du formulaire et l'envoi
	document.getElementById("reinit").addEventListener("click",	e => {
		reinitInputCmpt(e);
	});
	document.getElementById("enreg").addEventListener("click",
	e => {
		modifInputCmpt(e);
	});
	document.querySelector("form").addEventListener("submit", e => {
		modifInputCmpt(e);
	});

		// Gère le changement couleur de l'icône
	chgmtCouleurIcone();

	<?php if ($role === "admin") include "assets/scriptSelecteurAdmin.php"?>

		// Récupérer l'ID utilisateur (à gérer par l'équipe gestion de compte)
	const containerInput = document.getElementById("idAgri");
	containerInput.placeholder = idUtilisateur;

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