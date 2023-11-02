/**
 * Gestion des événements des informations du compte
 */
function gestionInputCmpt() {
	// Récupérer toutes les balises input
	const inputs = document.querySelectorAll("input");

	// Ajouter un événement "keypress" à chaque input
	inputs.forEach(input => {
		input.addEventListener("keypress", event => {
			if (event.key === "Enter") {
				if (input.name === "courriel") {
					if (!verifFormatCourriel(input.value)) {
						input.classList.add("erreur");

						// Envoi au backend
						envoiInfoCmptBack(input.name, input.value);
					}
					else {
						input.classList.remove("erreur");
					}
				}
			}
		});
	});
}

/**
 * Vériier le format de l'adresse courriel
 *
 * @param {string} email à vérifier
 * @return {boolean} true si le format est valide, false sinon
 */
function verifFormatCourriel(email) {
	const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	return regex.test(email);
}

function envoiInfoCmptBack(typeInfo, valeur) {
	const xhr = new XMLHttpRequest();
	xhr.open("POST", "/backend/enregistrerInfoCmpt", true);
	xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	xhr.send(JSON.stringify({champ: typeInfo, valeur: valeur}));
}