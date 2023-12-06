<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<title>Gestion champs</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#fbfbfc" media="(prefers-color-scheme: light)"/>
	<meta name="theme-color" content="#080809" media="(prefers-color-scheme: dark)"/>
	<link rel="icon" type="image/webp" href="img/favicon.webp"/>
	<meta name="description" content="Gestion de champs"/>
	<link rel="stylesheet" type="text/css" href="style/index.css"/>
</head>
<body tabindex="0">
<header>
	<?php include "snippets/logo.php"?>
	<section>
		<p>-</p>
		<div id="ddChamp" class="dropdown">
			<button class="dropbtn">⇩</button>
			<div id="selectChamp" class="dropdownContent ddHeader">
			</div>
		</div>
	</section>
	<section>
		<p>-</p>
		<div id="ddCmpt" class="dropdown">
			<button class="dropbtn">⇩</button>
			<div id="selectCmpt" class="dropdownContent ddHeader">
				<a href="gestionCmpt.php">Paramètres</a>
				<button value="deco">Déconnexion</button>
			</div>
		</div>
	</section>
</header>
<div id="corps">
	<section id="secInfos">
		<div>
			<h1>État général</h1>
			<p>-</p>
		</div>
		<div>
			<h1>Nombre de capteurs</h1>
			<div id="nbrCapteurs">
				<p>-</p>
				<p>/</p>
				<p>-</p>
			</div>
		</div>
		<div>
			<h1>Dernière màj</h1>
			<p>-</p>
		</div>
	</section>

	<section id="compMetMoy">
		<section id="secMeteo" class="tableau">
			<div>
				<div class="cellule titre">
					<p>
						Date
					</p>
					<div id="ddDuree" class="dropdown">
						<button class="dropbtn">⇩</button>
						<div id="selectDuree" class="dropdownContent">
							<button value="semaine">Semaine</button>
							<button value="jour">Journée</button>
						</div>
					</div>
				</div>
				<div class="cellule titre">Temp. max (°C)</div>
				<div class="cellule titre">Temp. min (°C)</div>
				<div class="cellule titre">Temp. (°C)</div>
				<div class="cellule titre">Humi. (%)</div>
				<div class="cellule titre">Précip. (mm/m²)</div>
				<div class="cellule titre">Type précip.</div>
				<div class="cellule titre">Dir. vent (°)</div>
				<div class="cellule titre">Couv. nua. (%)</div>
				<div class="cellule titre">UV</div>
				<div class="cellule titre">Vit. vent (km/h)</div>
			</div>
			<div id="donneesMeteo">
			</div>
		</section>
		<section id="secMoyennes">
			<div>
				<h1>Température moyenne</h1>
				<p>-</p>
			</div>
			<div>
				<h1>Humidité moyenne du sol</h1>
				<p>-</p>
			</div>
			<div>
				<h1>Luminosité moyenne</h1>
				<p>-</p>
			</div>
		</section>
	</section>

	<section id="secGraph">
		<div id="optGraph">
			<div>
				<p>Type de données</p>
				<div id="ddType" class="dropdown">
					<button class="dropbtn">⇩</button>
					<div id="selectType" class="dropdownContent">
						<button value="temp">Température</button>
						<button value="humi">Humidité du sol</button>
						<button value="lumi">Luminosité</button>
					</div>
				</div>
			</div>
			<div>
				<p>Ilot</p>
				<div id="ddIlot" class="dropdown">
					<button class="dropbtn">⇩</button>
					<div id="selectIlot" class="dropdownContent">
					</div>
				</div>
			</div>
		</div>
		<div id="graph">
		</div>
	</section>

	<section id="secTableau" class="tableau">
		<div>
			<div class="cellule titre">Ilot</div>
			<div class="cellule titre">Date et heure</div>
			<div class="cellule titre">État</div>
			<div class="cellule titre">Température (°C)</div>
			<div class="cellule titre">Humidité du sol (%)</div>
			<div class="cellule titre">Luminosité (lux)</div>
		</div>
		<div id="donneesTableau">
		</div>
	</section>
</div>
<?php include "snippets/footer.php"?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.27.0/plotly-basic.min.js"
integrity="sha512-TiY/d3GpuSKgQYgnqegSfdqlXp6ebBvi6A47mFTMSpZM7BMbvfkkvU/SlDzZHs9lWqF+BteevHlqgauHhLLbIA=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.27.0/plotly-locale-fr.min.js"
integrity="sha512-nyAFXuhmcYPFCAawwaZOW22viMZW5Aw1jB7w84GbnbPqIz1SDHWGdQw17DB2BfU1jv4nnEdJgvolNINTjdSKMA=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="scripts/interactionsBtn.js"></script>
<script src="scripts/recupDonnees.js"></script>
<script src="scripts/afficherDonnees.js"></script>
<script src="scripts/classesCouleurs.js"></script>
<script src="scripts/graphique.js"></script>
<script src="scripts/entete.js"></script>
<script>
	/*** Variables et constantes ***/
		/*
		 * Tableau de tableau contenant les ID des containers des dropdowns et
		 * les ID des boutons à ajouter quand ils sont sélectionnés
		*/
	const contIdButtons = [
		["selectChamp", "champSlct", true],
		["selectCmpt", "cmptSlct", false],
		["selectType", "typeSlct", true],
		["selectIlot", "ilotSlct", true],
		["selectDuree", "dureeSlct", true]
	];

		// Préselectionne les boutons des dropdowns
	contIdButtons.forEach(element => {
		activerBouton(element[0], element[1], element[2]);
	});

		// Retours des paramètres du graphique
	let typeMesuresStr, unite;
	let rangeMin, rangeMinMob, rangeMax, ordMin, ordMax;


	/*** Affichage des données ***/
		// Récupérer l'ID utilisateur (à gérer par l'équipe gestion de compte)
	let idUtilisateur = 0;

		// Affiche le nom de l'utilisateur dans le header
	afficherNomUtilisateur(idUtilisateur);

		// Affiche les champs de l'utilisateur
	afficherChamps(idUtilisateur)
	.then(_ => {
		// Active les boutons des champs
		activerBouton(contIdButtons[0][0], contIdButtons[0][1],
			contIdButtons[0][2]);
		
		/*
		 * Active la fonction de changement de champ pour charger les nouveaux
		 * ilots dans le dropdown
		 */
		activerBoutonChgmtChamp(idUtilisateur);

		// Affiche la météo (nécessite de connaitre le champ sélectionné)
		afficherMeteo(idUtilisateur);

		// Affiche les moyennes des valeurs des capteurs
		afficherMoyennes(idUtilisateur);
		afficherInfosChamp(idUtilisateur);

		// Affiche toutes les mesures du champ sélectionné
		afficherMesuresChamp(idUtilisateur);

		// Affiche les ilots du champ sélectionné
		afficherIlots(idUtilisateur)
		.then(_ => {
			// Active les boutons des ilots
			activerBouton(contIdButtons[3][0], contIdButtons[3][1],
				contIdButtons[3][2]);

			// Affiche le graphique (nécessite de connaitre l'ilot sélectionné)
			helperAfficherGraph();
		});
	});


	/*** Interaction des boutons ***/
		// Bouton pour changer la temporalité de la météo
	let dropdownDuree = document.getElementById("ddDuree");
	dropdownDuree.addEventListener("click", _ => {
		const valChamp = document.getElementById("dureeSlct").value;
		afficherMeteo(idUtilisateur);
	});

		// Bouton pour changer la sélection du graphique (type de données/ilot)
	let dropdownTypeGraph = document.getElementById("ddType");
	dropdownTypeGraph.addEventListener("click", _ => {
		helperAfficherGraph();
	});
	let dropdownIlotGraph = document.getElementById("ddIlot");
	dropdownIlotGraph.addEventListener("click", _ => {
		helperAfficherGraph();
	});


	/*** Changements du DOM ***/
		// Gère le défilement vers le haut de la page
	activerDefilementHautPage();

		// Gère la taille du header en fonction du défilement
	activerHeaderReduit();

		// Gère les changements de thème pour le graphique
	window.matchMedia("(prefers-color-scheme: light)")
	.addEventListener("change",	() => {
		helperActualisationStyleGraph();
	});

		// Gère les changements de taille d'écran pour le graphique
	window.matchMedia("(max-width: 769px)").addEventListener("change",
	() => {
		helperActualisationStyleGraph();
	});
</script>
</body>
</html>