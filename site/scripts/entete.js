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
		// Header
		document.querySelector("header").classList
			.add("miseEchelleHeader");

		// Box centrale
		document.querySelector("header > img").classList
			.add("masqueLogo");

		// Sections
		document.querySelectorAll("header > section")
			.forEach(function(section) {
				section.classList.add("miseEchelleSection");
			});
	}
	else {
		// Header
		document.querySelector("header").classList
			.remove("miseEchelleHeader");

		// Box centrale
		document.querySelector("header > img").classList
			.remove("masqueLogo");

		// Sections
		document.querySelectorAll("header > section")
			.forEach(function(section) {
				section.classList.remove("miseEchelleSection");
			});
	}
}