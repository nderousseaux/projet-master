/**
 * Vérifie les champs de Prénom, Nom et Courriel du formulaire, lorsqu'un
 * événement se produit
 *
 * @param {Event} e - événement sur le formulaire
 * @returns {boolean} - true si un champ est incorrect, false sinon
 */
function verifInputCmpt(e) {
	e.preventDefault();
	let contientErr = false;

	// Regex
	const regexInput = /^[\S\s]{1,100}$/;

	/* Fonction de vérification des champs */
		// Au chargement de la page
	function inputPreVerif(donnee) {
		if (donnee.value.match(regexInput) == null) {
			donnee.classList.add("erreur");
			contientErr = true;
		}
	}

		// Pendant que le champ est rempli
	const inputPostVerif = function() {
		if (this.value.match(regexInput) == null) {
			this.classList.add("erreur");
			contientErr = true;
		}
		else{
			this.classList.remove("erreur");
			contientErr = false;
		}
	}

	/* Vérification des champs */
		// Prénom
	let prenomInput = document.getElementById("prenom");
	inputPreVerif(prenomInput);
	prenomInput.addEventListener("input", inputPostVerif);

		// Nom
	let nomInput = document.getElementById("nom");
	inputPreVerif(nomInput);
	nomInput.addEventListener("input", inputPostVerif);

		// Courriel
	contientErr = verifInputCourriel();

	return contientErr;
}

/**
 * Vérifie le champ du rôle dans le formulaire, lorsqu'un événement se produit
 *
 * @returns {boolean} - true si le champ est incorrect, false sinon
 */
function verifSelectRole() {
	let contientErr = false;

	let roleInput = document.getElementById("role");
	if (roleInput.value !== "admin" && roleInput.value !== "standard") {
		roleInput.classList.add("erreur");
		contientErr = true;
	}
	roleInput.addEventListener("input", function() {
		if (roleInput.value !== "admin" && roleInput.value !== "standard") {
			this.classList.add("erreur");
			contientErr = true;
		}
		else {
			this.classList.remove("erreur");
			contientErr = false;
		}
	});

	return contientErr;
}

/**
 * Vérifie le champ du courriel dans le formulaire, lorsqu'un événement se
 * produit
 *
 * @returns {boolean} - true si le champ est incorrect, false sinon
 */
function verifInputCourriel() {
	let contientErr = false;

	// Regex
	const regexCourriel = /^[a-z0-9-_.]+@[a-z0-9-_.]+\.[a-z]{1,}$/;

	let courrielInput = document.getElementById("courriel");
	if (courrielInput.value.match(regexCourriel) == null) {
		courrielInput.classList.add("erreur");
		contientErr = true;
	}
	courrielInput.addEventListener("input", function() {
		if (this.value.match(regexCourriel) == null) {
			this.classList.add("erreur");
			contientErr = true;
		}
		else {
			this.classList.remove("erreur");
			contientErr = false;
		}
	});

	return contientErr;
}