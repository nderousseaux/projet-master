/**
 * Modifie les couleurs de l'icône quand l'utilisateur change les couleurs
 * dans le formulaire
 */
function chgmtCouleurIcone() {
	const icone = document.querySelector("#icone > div");
	const couleur1 = document.getElementById("couleur1");
	const couleur2 = document.getElementById("couleur2");

	// Initialise les couleurs de l'icône
	icone.style.background =  "linear-gradient(" + couleur1.value  + ", " +
		couleur2.value + ")";

	// Ajoute des évenements sur les selecteurs de couleur
	couleur1.addEventListener("change", () => {
		icone.style.background =  "linear-gradient(" + couleur1.value  + ", " +
			couleur2.value + ")";
	});
	couleur2.addEventListener("change", () => {
		icone.style.background =  "linear-gradient(" + couleur1.value  + ", " +
			couleur2.value + ")";
	});
}

/**
 * Vérifie les champs du formulaire, lorsqu'un événement se produit
 *
 * @param {Event} e - événement
 * @returns {boolean} - true si un champ est incorrect, false sinon
 */
function verifInputCmpt(e) {
	e.preventDefault();
	let contientErr = false;

	// Regex
	const regexInput = /^[\S\s]{1,100}$/;
	const regexCourriel = /^[a-z0-9-_.]+@[a-z0-9-_.]+\.[a-z]{1,}$/;

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

/**
 * Vérifie les champs du formulaire, lorsqu'un événement se produit et
 * envoie les données au backend si elles sont correctes, pour la modification
 * des informations d'un compte
 *
 * @param {Event} e - événement
 */
function modifInputCmpt(e) {
	let contientErr = verifInputCmpt(e);

	/* Envoi des données au backend */
	if (contientErr === false) {
		const donneesForm = new FormData(document.querySelector("form"));
		let champPost = new FormData();

		// Trie les valeurs qui ont été modifiées
		for (let [key, value] of donneesForm.entries()) {
			let placeholder = document.querySelector(
				"form > input[name=" + key + "]").placeholder;

			if (key === "mdp") {
				if (value !== '') {
					champPost.append(key, value);
				}
			}
			else {
				if (value !== placeholder) {
					champPost.append(key, value);
				}
			}
		}

		// N'envoi les données que si au moins un champ a été modifié
		if (champPost.entries().next().done === false) {
			recupDonnees(champPost, "modifCmpt.php")
			.then(_ => {
				champPost.forEach((value, key) => {
					document.querySelector(
						"form > input[name=" + key + "]").placeholder = value;
				});
			})
			.catch(err => {
				console.log(err);
			});
		}
	}
}

/**
 * Vérifie les champs du formulaire, lorsqu'un événement se produit et
 * envoie les données au backend si elles sont correctes pour la création
 * d'un compte
 * 
 * @param {Event} e - événement
 */
function creationCmpt(e) {
	let contientErr = verifInputCmpt(e);

	/* Vérification des champs */
		// Rôle
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

	/* Envoi des données au backend */
	if (contientErr === false) {
		const champPost = new FormData(document.querySelector("form"));

		recupDonnees(champPost, "creationUtilisateur.php")
		.catch(err => {
			console.log(err);
		});
	}
}

/**
 * Gère les champs du formulaire de connexion
 */
function connexionCmpt(e) {
	e.preventDefault();
	let contientErr = false;

	// Regex
	const regexCourriel = /^[a-z0-9-_.]+@[a-z0-9-_.]+\.[a-z]{1,}$/;

	/* Vérification des champs */
		// Courriel
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

		// Mot de passe
	let mdpInput = document.getElementById("mdp");
	if (mdpInput.value === '') {
		mdpInput.classList.add("erreur");
		contientErr = true;
	}
	mdpInput.addEventListener("input", function() {
		if (mdpInput.value === '') {
			this.classList.add("erreur");
			contientErr = true;
		}
		else {
			this.classList.remove("erreur");
			contientErr = false;
		}
	});

	/* Envoi des données au backend */
	if (contientErr === false) {
		const champPost = new FormData(document.querySelector("form"));

		recupDonnees(champPost, "connexionUtilisateur.php")
		.then(_ => {
			window.location.href = "index.php";
		})
		.catch(err => {
			console.log(err);
		});
	}
}

/**
 * Réinitialise les valeurs des inputs du formulaire
 *
 * @param {Event} e - événement
 */
function reinitInputCmpt(e) {
	e.preventDefault();

	// Récupère tous les éléments du formulaire
	const inputsForm = document.querySelectorAll("form input");

	// Remplace les valeurs des inputs par les placeholders (sauf pour le mdp)
	inputsForm.forEach(input => {
		if (input.id !== "mdp") {
			const placeholder = input.placeholder;
			input.value = placeholder;
		}
	});
}

/**
 * Copie l'identifiant de l'agriculteur dans le presse-papier
 */
function copierPressePapier() {
	const containerInput = document.getElementById("idAgri");
	containerInput.select();
	navigator.clipboard.writeText(containerInput.placeholder);

	// Affiche ce qui a été copié
	const containerBouton = document.getElementById("texteBouton");
	containerBouton.innerHTML = "Copié : " + containerInput.placeholder;
}

/**
 * Réinitialise le contenu affiché sur le bouton de copie
 */
function reinitBouton() {
	var containerBouton = document.getElementById("texteBouton");
	containerBouton.innerHTML = "Copier dans le presse-papier";
}