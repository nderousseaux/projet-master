/**
 * Vérifie les champs du formulaire, lorsqu'un événement se produit
 *
 * @param {Event} e - événement
 */
function gestionInputCmpt(e) {
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

	/* Envoi des données au backend */
	if (contientErr == false) {
		let donneesForm = new FormData(document.querySelector("form"));
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
 * Gère les champs du formulaire de création de compte
 */
function creationCmpt() {
	document.querySelector("form > input[type='submit']")
	.addEventListener("click", event => {
		event.preventDefault();

		// Vérifie que les champs sont remplis
		let prenom = document
			.querySelector("form > input[type='text'][name='prenom']");
		let nom = document
			.querySelector("form > input[type='text'][name='nom']");
		let courriel = document
			.querySelector("form > input[type='text'][name='courriel']");
		// let mdp = document
		// 	.querySelector("form > input[type='password'][name='mdp']");
		let role = document
			.querySelector("form > input[type='text'][name='role']");

		const champs = [prenom, nom, role];

		// Prénom, nom et role
		champs.forEach(element => {
			if (element.value.length === 0) {
				element.classList.add("erreur")
			}
			else {
				element.classList.remove("erreur")
			}
		});

		// Courriel
		if (
			courriel.value.length === 0 ||
			!verifFormatCourriel(courriel.value)
		) {
			courriel.classList.add("erreur")
		}
		else {
			courriel.classList.remove("erreur")
		}

		// Vérifie le nombre de classes "erreur"
		if (document.querySelectorAll(".erreur").length === 0) {
			document.querySelector("form").submit()
		}
	});
}

/**
 * Gère les champs du formulaire de connexion
 */
function connexionCmpt() {
	document.querySelector("form > input[type='submit']")
	.addEventListener("click", event => {
		event.preventDefault();

		// Vérifie que les champs sont remplis
		let courriel = document
			.querySelector("form > input[type='text'][name='courriel']");
		let mdp = document
			.querySelector("form > input[type='password'][name='mdp']");

		const champs = [mdp];

		//  mdp
		champs.forEach(element => {
			if (element.value.length === 0) {
				element.classList.add("erreur")
			}
			else {
				element.classList.remove("erreur")
			}
		});

		// Courriel
		if (
			courriel.value.length === 0 ||
			!verifFormatCourriel(courriel.value)
		) {
			courriel.classList.add("erreur")
		}
		else {
			courriel.classList.remove("erreur")
		}

		// Vérifie le nombre de classes "erreur"
		if (document.querySelectorAll(".erreur").length === 0) {
			document.querySelector("form").submit()
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