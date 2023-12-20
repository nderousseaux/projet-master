/**
 * Vérifie les champs de Prénom, Nom et Courriel du formulaire, lorsqu'un
 * événement se produit
 *
 * @param {Event} e - événement sur le formulaire
 * @returns {int} - le nombre de champs incorrects
 */
function verifInputCmpt(e) {
	e.preventDefault();
	let nbrErr = 0;

	// Regex
	const regexInput = /^[\S\s]{1,100}$/;

	/* Fonction de vérification des champs */
		// Au chargement de la page
	function inputPreVerif(donnee) {
		if (donnee.value.match(regexInput) == null) {
			donnee.classList.add("erreur");
			nbrErr++;
		}
	}

		// Pendant que le champ est rempli
	const inputPostVerif = function() {
		if (this.value.match(regexInput) == null) {
			this.classList.add("erreur");
			nbrErr++;
		}
		else {
			this.classList.remove("erreur");
			nbrErr--;
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
	nbrErr += verifInputCourriel();

	return nbrErr;
}

/**
 * Vérifie le champ du rôle dans le formulaire, lorsqu'un événement se produit
 *
 * @returns {int} - le nombre de champs incorrects
 */
function verifSelectRole() {
	let nbrErr = 0;

	let roleInput = document.getElementById("role");
	if (roleInput.value !== "admin" && roleInput.value !== "standard") {
		roleInput.classList.add("erreur");
		nbrErr++;
	}
	roleInput.addEventListener("input", function() {
		if (roleInput.value !== "admin" && roleInput.value !== "standard") {
			this.classList.add("erreur");
			nbrErr++;
		}
		else {
			this.classList.remove("erreur");
			nbrErr--;
		}
	});

	return nbrErr;
}

/**
 * Vérifie le champ du courriel dans le formulaire, lorsqu'un événement se
 * produit
 *
 * @returns {int} - true si le champ est incorrect, false sinon
 */
function verifInputCourriel() {
	let nbrErr = 0;

	// Regex
	const regexCourriel = /^[a-z0-9-_.]+@[a-z0-9-_.]+\.[a-z]{1,}$/;

	let courrielInput = document.getElementById("courriel");
	if (courrielInput.value.match(regexCourriel) == null) {
		courrielInput.classList.add("erreur");
		nbrErr++;
	}
	courrielInput.addEventListener("input", function() {
		if (this.value.match(regexCourriel) == null) {
			this.classList.add("erreur");
			nbrErr++;
		}
		else {
			this.classList.remove("erreur");
			nbrErr--;
		}
	});

	return nbrErr;
}