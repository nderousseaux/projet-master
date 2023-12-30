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
 * Vérifie les champs du formulaire, lorsqu'un événement se produit et
 * envoie les données au backend si elles sont correctes, pour la modification
 * des informations d'un compte
 *
 * @param {Event} e - événement
 * @param {boolean} requeteAdmin - true si la requête est faite par un admin,
 * 								   vérifie le rôle sélectionné dans ce cas
 */
function modifInputCmpt(e, requeteAdmin = false) {
	/* Vérification des champs */
		// Prénom, Nom et Courriel
	let nbrErr = verifInputCmpt(e);

		// Rôle
	if (requeteAdmin === true) {
		nbrErr += verifSelectRole();
	}

		// Couleur
	nbrErr += verifCouleur();

	/* Envoi des données au backend */
	if (nbrErr === 0) {
		const donneesForm = new FormData(document.querySelector("form"));
		let champPost = new FormData();

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
				console.log(err);
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
 *
 * @param {Event} e - événement
 */
function creationCmpt(e) {
	/* Vérification des champs */
		// Prénom, Nom et Courriel
	let nbrErr = verifInputCmpt(e);

		// Rôle
	nbrErr += verifSelectRole();

	/* Envoi des données au backend */
	if (nbrErr === 0) {
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
			// Redige vers la page d'accueil
			if (donnees[0] === 0) {
				window.location.href = "index.php";
			}
			// Demande de changer mot de passe (à la première connexion)
			else if (donnees[0] === 1) {
				changerFormulaire();
			}
			// Erreur dans les identifiants
			else {
				afficherMsgErreur(donnees[1]);
			}
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
		else {
			input.value = '';
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
	let containerBouton = document.getElementById("texteBouton");
	containerBouton.innerHTML = "Copier dans le presse-papier";
}

/**
 * Change le formulaire pour permettre à l'utilisateur de changer son mot de
 * passe lors de sa première connexion
 */
function changerFormulaire() {
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
		<button id="enregMdp">Enregistrer</button>
	`;
	container.appendChild(nouveauForm);

	// Change le titre du container
	const titre = container.querySelector("h1");
	titre.textContent = "Définir un nouveau mot de passe";

	// Ajoute les événements au bouton d'enregistrement et au formulaire
	document.getElementById("enregMdp").addEventListener("click",
	e => {
		enregistrerMdp(e);
	});
	document.querySelector("form").addEventListener("submit", e => {
		enregistrerMdp(e);
	});
}

/**
 * Vérifie le champ du mot de passe dans le formulaire, lorsqu'un événement se
 * produit et envoie le mot de passe au backend s'il respecte les critères
 *
 * @param {Event} e - événement
 */
function enregistrerMdp(e) {
	e.preventDefault();

	let nbrErr = verifInputMdp();

	if (nbrErr === 0) {
		const champPost = new FormData();
		champPost.append("mdp", mdp);

		recupDonnees(champPost, "modifCmpt.php")
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
function afficherMsgErreur(message) {
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
	document.getElementById("mdp").classList.add("erreur");
}