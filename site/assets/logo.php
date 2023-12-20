<?php 
$page = $_SERVER["REQUEST_URI"];
$href = '';
$onClick = '';

if ($page != '/' && $page != "/index.php") {
	$href = " href='.'";
}
else {
	$onClick = " onClick='window.scrollTo(0, 0);'";
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
				title="Retourner en haut de la page"
				src="img/logo/logoAlsagricultureClair.webp"
				alt="Logo Als'agriculture"/>
		</picture>
	</a>
