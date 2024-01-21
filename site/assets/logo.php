<?php 
$page = $_SERVER["REQUEST_URI"];
$href = '';
$onClick = '';

// Si on est sur la page d'accueil ou de connexion, on scroll vers le haut
if (
	$page === "/index.php" ||
	$page === '/' ||
	$page === "/connexionCmpt.php"
) {
	$onClick = " onClick='window.scrollTo(0, 0);'";
	$title = "Retourner en haut de la page";
}

// Sinon on renvoie vers l'accueil
else {
	$href = " href='.'";
	$title = "Retourner à l'accueil";
}
?>
<a<?php echo $href?>>
		<picture>
			<source srcset="img/logo/logoAlsagricultureMobileClair.webp"
				media="(max-width: 950px) and (prefers-color-scheme: light)"/>
			<source srcset="img/logo/logoAlsagricultureMobileSombre.webp"
			media="(max-width: 950px) and (prefers-color-scheme: dark)"/>
			<source srcset="img/logo/logoAlsagricultureClair.webp"
				media="(prefers-color-scheme: light)"/>
			<source srcset="img/logo/logoAlsagricultureSombre.webp"
				media="(prefers-color-scheme: dark)"/>
			<img<?php echo $onClick . PHP_EOL?>
				title="<?php echo $title?>"
				src="img/logo/logoAlsagricultureClair.webp"
				alt="Logo Als'agriculture"/>
		</picture>
	</a>
