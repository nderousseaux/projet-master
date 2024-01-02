/**
 * Vérifie les champs de Nom, Prénom, Courriel et Message du formulaire,
 * lorsqu'un événement se produit
 *
 * @param {Event} e - événement sur le formulaire
 * @returns {int} - le nombre de champs incorrects
 */
function verifInputCmpt(e) {
	e.preventDefault();
	let nbrErr = 0;

	// Regex
	const regexInput = /^[\S\s]{1,100}$/;
	const regexCourriel = /^[a-z0-9-_.]+@[a-z0-9-_.]+\.[a-z]{1,}$/;

	/* Fonction de vérification des champs */
		// Au chargement de la page
	function inputPreVerif(donnee) {
		if (donnee.value.match(regexInput) === null) {
			donnee.classList.add("erreur");
			nbrErr++;
		}
	}
	function inputPreVerifCourriel(donnee) {
		if (donnee.value.match(regexCourriel) === null) {
			donnee.classList.add("erreur");
			nbrErr++;
		}
	}
	function inputPreVerifMessage(donnee) {
		if (donnee.value === '') {
			donnee.classList.add("erreur");
			nbrErr++;
		}
	}

		// Pendant que le champ est rempli
	const inputPostVerif = function() {
		if (this.value.match(regexInput) === null) {
			this.classList.add("erreur");
			nbrErr++;
		}
		else {
			this.classList.remove("erreur");
			nbrErr--;
		}
	}
	const inputPostVerifCourriel = function() {
		if (this.value.match(regexCourriel) === null) {
			this.classList.add("erreur");
			nbrErr++;
		}
		else {
			this.classList.remove("erreur");
			nbrErr--;
		}
	}
	const inputPostVerifMessage = function() {
		if (this.value === '') {
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
	let courrielInput = document.getElementById("courriel");
	inputPreVerifCourriel(courrielInput);
	courrielInput.addEventListener("input", inputPostVerifCourriel);

		// Message
	let messageInput = document.getElementById("message");
	inputPreVerifMessage(messageInput);
	messageInput.addEventListener("input", inputPostVerifMessage);

	return nbrErr;
}

/**
 * Envoie les données du formulaire au backend
 *
 * @param {Event} e - événement sur le formulaire
 */
function enregistrementContact(e) {
	e.preventDefault();

	const formulaire = document.querySelector("#formulaire > form");
	let nbrErreur = verifInputCmpt(e);

	// Créé un objet FormData avec les données du formulaire
	const champPost = new FormData(formulaire);
	champPost.append("message", message.value);

	if (nbrErreur === 0) {
		// Envoie les données du formulaire au backend
		recupDonnees(champPost, "nouveauMsg.php")
		.then(donnees => {
			const container = document.querySelector("#formulaire");

			// Affiche un message de confirmation ou d'erreur
			if (donnees === 0) {
				container.innerHTML = "<p>Votre message a été envoyé avec " +
					"succès !</p>";
			}
			else if (donnees === 1) {
				container.innerHTML = "<p>Le formulaire envoyé n'est pas " +
					"formaté correctement.<br/>Veuillez réessayer plus tard." +
					"</p>";
			}
			else {
				container.innerHTML = "<p>Une erreur est survenue lors de " +
					"l'envoi de votre message. <br/>Veuillez réessayer plus " +
					"tard.</p>";
			}
		})
		.catch(err => {
			console.error(err);
		});
	}
}