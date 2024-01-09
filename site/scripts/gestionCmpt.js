/**
 * Modifie les couleurs de l'icône quand l'utilisateur change les couleurs
 * dans le formulaire
 */
function chgmtCouleurIcone() {
	const icone = document.querySelector("#icone > div");
	const couleur1 = document.getElementById("couleur1");
	const couleur2 = document.getElementById("couleur2");

	if (verifCouleur() !== 0) {
		console.erreur("Le format des couleurs est incorrect")
		return;
	}

	// Initialise les couleurs de l'icône
	icone.style.background = "linear-gradient(" + couleur1.value + ", " +
		couleur2.value + ")";

	// Ajoute des évenements sur les selecteurs de couleur
	couleur1.addEventListener("change", () => {
		icone.style.background = "linear-gradient(" + couleur1.value + ", " +
			couleur2.value + ")";
	});
	couleur2.addEventListener("change", () => {
		icone.style.background = "linear-gradient(" + couleur1.value + ", " +
			couleur2.value + ")";
	});
}

/**
 * Vérifie les champs du formulaire, lorsqu'un événement se produit et
 * envoie les données au backend si elles sont correctes, pour la modification
 * des informations d'un compte
 *
 * @param {boolean} requeteAdmin - true si la requête est faite par un admin,
 * 								   vérifie le rôle sélectionné dans ce cas
 */
function modifInputCmpt(requeteAdmin = false) {
	/* Vérification des champs */
		// Prénom, Nom et Courriel
	let nbrErr = verifInputCmpt();

		// Mot de passe
	nbrErr += verifInputMdp();

		// Rôle
	if (requeteAdmin === true) {
		nbrErr += verifSelectRole();
	}

		// Couleur
	nbrErr += verifCouleur();

	/* Envoi des données au backend */
	if (nbrErr === 0) {
		// Récupère les données du formulaire
		const donneesForm = new FormData(document.querySelector("form"));
		let champPost = new FormData();
		champPost.append("idUtilisateur",
			document.getElementById("idUtili").value);

		// Trie les valeurs qui ont été modifiées
		for (let [key, value] of donneesForm.entries()) {
			// Vérifie si le rôle a été modifié
			if (key === "role") {
				const optionSelect =
					document.getElementById("selectionne").value;

				if (value !== optionSelect) {
					champPost.append(key, value);
				}
				continue;
			}

			// Vérifie si le mot de passe a été modifié
			if (key === "mdp") {
				if (value !== '') {
					champPost.append(key, value);
				}
				continue;
			}

			// Vérifie si les autres champs ont été modifiés
			const placeholder = document.querySelector(
				"form > input[name=" + key + "]").placeholder;
			if (value !== placeholder) {
				champPost.append(key, value);
			}
		}

		// N'envoi les données que si au moins un champ a été modifié
		if (champPost.entries().next().done === false) {
			recupDonnees(champPost, "modifCmpt.php")
			.then(_ => {
				majValInputCmpt(champPost);
			})
			.catch(err => {
				console.error(err);
			});
		}
	}
}

/**
 * Met à jour les valeurs des inputs du formulaire avec les valeurs enregistrées
 * dans la base de données
 *
 * @param {formData} champPost - données du formulaire changées
 */
function majValInputCmpt(champPost) {
	champPost.forEach((value, key) => {
		// Remplace les valeurs par celles enregistrées dans la base
			// Rôle
		if (key === "role") {
			const optionSelect = document.getElementById(
				"selectionne"
			);
			optionSelect.removeAttribute("id");

			if (value !== optionSelect) {
				document.querySelector(
					"#role > option[value=" + value +"]"
				).id = "selectionne";
			}
		}
			// Mot de passe
		else if (key === "mdp") {
			document.querySelector(
				"form > input[name=" + key + "]"
			).value = '';
		}
			// Autres champs
		else {
			document.querySelector(
				"form > input[name=" + key + "]"
			).placeholder = value;
		}
	});
}

/**
 * Vérifie les champs du formulaire, lorsqu'un événement se produit et
 * envoie les données au backend si elles sont correctes pour la création
 * d'un compte
 */
function creationCmpt() {
	/* Vérification des champs */
		// Prénom, Nom et Courriel
	let nbrErr = verifInputCmpt();

		// Rôle
	nbrErr += verifSelectRole();

	/* Envoi des données au backend */
	if (nbrErr === 0) {
		const champPost = new FormData(document.querySelector("form"));

		recupDonnees(champPost, "creationUtilisateur.php")
		.then(donnees => {
			if (donnees[0] === 0) {
				afficherMsgErreur(donnees[1]);
			}
			else if (donnees[0] === 1) {
				afficherMsgErreur(donnees[1], true);
			}
		})
		.catch(err => {
			console.error(err);
		});
	}
}

/**
 * Gère les champs du formulaire de connexion
 */
function connexionCmpt(e) {
	e.preventDefault();

	/* Vérification des champs */
		// Courriel
	let nbrErr = verifInputCourriel();

		// Mot de passe (vérifie uniquement que le champ n'est pas vide)
	let mdpInput = document.getElementById("mdp");
	if (mdpInput.value === '') {
		mdpInput.classList.add("erreur");
		nbrErr++;
	}
	mdpInput.addEventListener("input", function() {
		if (mdpInput.value === '') {
			this.classList.add("erreur");
			nbrErr--;
		}
		else {
			this.classList.remove("erreur");
			nbrErr--;
		}
	});

	/* Envoi des données au backend */
	if (nbrErr === 0) {
		const champPost = new FormData(document.querySelector("form"));

		recupDonnees(champPost, "connexionUtilisateur.php")
		.then(donnees => {
			// Redirige vers la page d'accueil
			if (donnees[0] === 0) {
				window.location.href = "index.php";
			}
			// Demande de changer mot de passe (à la première connexion)
			else if (donnees[0] === 1) {
				changerFormulaire(donnees[1]);
			}
			// Erreur dans les identifiants
			else {
				afficherMsgErreur(donnees[1]);
			}
		})
		.catch(err => {
			console.error(err);
		});
	}
}

/**
 * Réinitialise les valeurs des inputs du formulaire
 */
function reinitInputCmpt(e) {
	// Récupère tous les éléments du formulaire
	const inputsForm = document.querySelectorAll("form input");

	// Remplace les valeurs des inputs par les placeholders (sauf pour le mdp)
	inputsForm.forEach(input => {
		if (input.id !== "mdp") {
			const placeholder = input.placeholder;
			input.value = placeholder;
		}
		else {
			input.value = '';
		}
	});
}

/**
 * Copie l'identifiant de l'utilisateur dans le presse-papier
 */
function copierPressePapier() {
	const containerInput = document.getElementById("idUtili");
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
	let containerBouton = document.getElementById("texteBouton");
	containerBouton.innerHTML = "Copier dans le presse-papier";
}

/**
 * Change le formulaire pour permettre à l'utilisateur de changer son mot de
 * passe lors de sa première connexion
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 */
function changerFormulaire(idUtilisateur) {
	const container = document.getElementById("infosCmpt");

	// Supprime l'ancien formulaire
	const ancienForm = document.getElementById("formCmpt");
	ancienForm.remove();

	// Crée le nouveau formulaire
	const nouveauForm = document.createElement("form");
	nouveauForm.id = "formMdp";
	nouveauForm.innerHTML = `
		<label class="colonne" for="mdp">Mot de passe</label>
		<div id="secMdp">
			<input type="password" id="mdp" name="mdp"
			class="colonne" placeholder="******" value=''></input>
			<span class="bulle">
				Minimum 14 caractères, 1 majuscule, 1 minuscule,
				1 chiffre et 1 caractère spécial
			</span>
		</div>
		<button type="button" id="enregMdp">Enregistrer</button>
	`;
	container.appendChild(nouveauForm);

	// Change le titre du container
	const titre = container.querySelector("h1");
	titre.textContent = "Définir un nouveau mot de passe";

	// Ajoute les événements au bouton d'enregistrement et au formulaire
	document.getElementById("enregMdp").addEventListener("click",
	e => {
		enregistrerMdp(e, idUtilisateur);
	});
	document.querySelector("form").addEventListener("submit", e => {
		enregistrerMdp(e, idUtilisateur);
	});
}

/**
 * Vérifie le champ du mot de passe dans le formulaire, lorsqu'un événement se
 * produit et envoie le mot de passe au backend s'il respecte les critères
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 */
function enregistrerMdp(idUtilisateur) {
	let nbrErr = verifInputMdp();

	if (nbrErr === 0) {
		const champPost = new FormData();
		champPost.append("idUtilisateur", idUtilisateur);
		champPost.append("mdp", document.getElementById("mdp").value);

		recupDonnees(champPost, "modifCmpt.php")
		.then(_ => {
			window.location.href = "index.php";
		})
		.catch(err => {
			console.error(err);
		});
	}
}

/**
 * Affiche un message d'erreur dans le formulaire
 *
 * @param {string} message - message d'erreur à afficher
 */
function afficherMsgErreur(message, creaUtili = false) {
	// Supprime le message d'erreur précédent
	if (document.getElementById("msgErr") !== null) {
		document.getElementById("msgErr").remove();
	}

	// Ajoute le message d'erreur au formulaire
	const container = document.getElementById("formCmpt");
	const msgErreur = document.createElement("p");
	msgErreur.textContent = message;
	msgErreur.id = "msgErr";
	container.prepend(msgErreur);

	// Ajoute la classe erreur aux champs du formulaire
	document.getElementById("courriel").classList.add("erreur");

	if (creaUtili === false) {
		document.getElementById("mdp").classList.add("erreur");
	}
}

/**
 * Supprime le compte de l'utilisateur
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 */
function supprCmpt(idUtilisateur) {
	const champPost = new FormData();
	const idUtiliForm = document.getElementById("idUtili").value;
	champPost.append("idUtilisateur", idUtilForm);

	recupDonnees(champPost, "supprCmpt.php")
	.then(retour => {
		/*
		 * Si l'utilisateur supprime son propre compte, il est redirigé vers
		 * la page de connexion
		 */
		if (retour === 0 && idUtilisateur === idUtiliForm) {
			window.location.href = "connexionCmpt.php";
		}
		else if (retour === 1) {
			console.erreur("Erreur lors de la suppression du compte");
		}
		else {
			window.location.href = "gestionCmpt.php";
		}
	})
	.catch(err => {
		console.error(err);
	});
}