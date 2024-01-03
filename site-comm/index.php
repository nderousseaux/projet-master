<?php
if (isset($_GET["lang"])) {
	if ($_GET["lang"] === "fr") {
		include "assets/tradFr.php";
	}
	elseif ($_GET["lang"] === "en") {
		include "assets/tradEn.php";
	}
	elseif ($_GET["lang"] === "de") {
		include "assets/tradDe.php";
	}
	else {
		include "assets/tradFr.php";
	}
}
else {
	include "assets/tradFr.php";
}
?><!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#4caf50"/>
	<link rel="icon" type="image/webp" href="img/favicon.webp"/>
	<link rel="stylesheet" type="text/css" href="style/index.css"/>
	<title>Accueil</title>
</head>
<body tabindex='0'>
	<header>
		<div>
			<img src="img/AlsAgriNet.svg" alt="<?=$trad["header"]["alt"];?>"/>
			<h1>AlsAgriNet</h1>
		</div>
		<div>
			<h2><?=$trad["header"]["slogan"];?></h2>
			<div id="ddLang" class="dropdown" title=''>
				<button class="dropbtn">🇫🇷</button>
				<div id="selectLang" class="dropdownContent ddHeader">
					<a id="fr" href="?lang=fr">🇫🇷 Français</a>
					<a id="en" href="?lang=en">🇬🇧 English</a>
					<a id="de" href="?lang=de">🇩🇪 Deutsch</a>
				</div>
			</div>
		</div>
	</header>
	<div id="corps">
		<section>
			<h2><?=$trad["pres"]["titre"];?></h2>
			<p>
				<?=$trad["pres"]["contenu"];?>
			</p>
		</section>
		<section>
			<h2><?=$trad["serv"]["titre"];?></h2>
			<ul>
				<?=$trad["serv"]["contenu"];?>
			</ul>
		</section>
		<section>
			<h2><?=$trad["apropos"]["titre"];?></h2>
			<p>
				<?=$trad["apropos"]["contenu"];?>
			</p>
			<div id="equipe">
				<h3><?=$trad["apropos"]["equipe"]["titre"];?></h3>
				<div>
					<p>
						<?=$trad["apropos"]["equipe"]["contenu"];?>
					</p>
					<img src="img/equipe.webp"
						alt=<?=$trad["apropos"]["equipe"]["alt"];?>/>
				</div>
			</div>
			<div id="reseaux">
				<h3><?=$trad["apropos"]["rs"]["titre"]?></h3>
				<div>
					<a href="https://www.facebook.com/AlsAgriNet"
						target="_blank" rel="noopener">
						<img src="img/icones/facebook.svg"
							alt=<?=$trad["apropos"]["rs"]["alt"]["facebook"]?>/>
						<p>Facebook</p>
					</a>
					<a href="https://www.x.com/AlsAgriNet" target="_blank"
						rel="noopener">
						<img src="img/icones/x.svg"
						alt=<?=$trad["apropos"]["rs"]["alt"]["x"]?>/>
						<p>X</p>
					</a>
					<a href="https://www.linkedin.com/alsagrinet/"
						target="_blank" rel="noopener">
						<img src="img/icones/linkedin.svg"
							alt=<?=$trad["apropos"]["rs"]["alt"]["linkedin"]?>/>
						<p>LinkedIn</p>
					</a>
				</div>
			</div>
		</section>
	</div>
	<div id="bulle">
		<img src="img/icones/interrogation.svg"
			alt="Icône de point d'interrogation"/>
	</div>
	<div id="formulaire">
		<h2><?=$trad["contact"]["titre"]?></h2>
		<form>
			<label for="nom"><?=$trad["contact"]["form"]["nom"]?></label>
			<input type="text" name="nom" id="nom"
				placeholder="<?=$trad["contact"]["form"]["nom"]?>" required/>
			<label for="prenom"><?=$trad["contact"]["form"]["prenom"]?></label>
			<input type="text" name="prenom" id="prenom"
				placeholder="<?=$trad["contact"]["form"]["prenom"]?>" required/>
			<label for="email"><?=$trad["contact"]["form"]["courriel"]?></label>
			<input type="email" name="courriel" id="courriel"
				placeholder="<?=$trad["contact"]["form"]["phCourriel"]?>"
				required/>
			<label for="message" aria-required>
				<?=$trad["contact"]["form"]["message"]?>
			</label>
			<textarea id="message"
				placeholder="<?=$trad["contact"]["form"]["phMessage"]?>"
				required></textarea>
			<input type="submit"
				value="<?=$trad["contact"]["form"]["envoi"]?>"/>
		</form>
	</div>
	<footer>
		<?=$trad["footer"]["droits"]?> - AlsAgriNet - 2024
	</footer>
<script src="scripts/recupDonnees.js"></script>
<script src="scripts/formulaire.js"></script>
<script>
	document.querySelector("#formulaire > form").addEventListener("submit",
	(e) => {
		enregistrementContact(e, "<?=$trad["langue"]["abrevia"];?>");
	});
	document.querySelector("#formulaire input[type=submit]").addEventListener(
	"click", e => {
		enregistrementContact(e, "<?=$trad["langue"]["abrevia"];?>");
	});

	document.querySelector("#bulle").addEventListener("click", () => {
		const formulaire = document.querySelector("#formulaire");
		formulaire.classList.toggle("visible");
	});

	// Selecteur de langue
	document.getElementById("<?=$trad["langue"]["abrevia"];?>").classList
		.add("selected");
	document.querySelector("#ddLang > button").innerHTML =
		"<?=$trad["langue"]["drap"];?>";
</script>
</body>
</html>