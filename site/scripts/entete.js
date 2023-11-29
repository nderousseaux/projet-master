/**
 * Active la gestion du header réduit en fonction de la distance de défilement
 */
function activerHeaderReduit() {
	// Détermine la distance de défilement avant rétractation du header
	if (window.matchMedia("(max-width: 769px)").matches) {
		distanceScroll = 90;
	}
	else {
		distanceScroll = 35;
	}

	window.onload = function() {retracterScroll()};
	window.onscroll = function() {retracterScroll()};
}

/**
 * Rétracte le header en fonction de la distance de défilement
 */
function retracterScroll() {
	if (document.documentElement.scrollTop > distanceScroll) {
		// Box centrale
		document.querySelector("header > img").classList
			.add("retractLogo");
	}
	else {
		// Box centrale
		document.querySelector("header > img").classList
			.remove("retractLogo");
	}
}