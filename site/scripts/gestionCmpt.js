/**
 * Vérifie les champs du formulaire, lorsqu'un événement se produit
 */
function gestionInputCmpt() {
	// Récupérer toutes les balises input
	const inputs = document.querySelectorAll("input");

	// Ajouter un événement "keypress" à chaque input
	inputs.forEach(input => {
		input.addEventListener("keypress", touche => {
			if (
				touche.key === "Enter" &&
				input.value != input.placeholder &&
				input.value.length > 0
			) {
				if (input.name === "courriel") {
					if (!verifFormatCourriel(input.value)) {
						input.classList.add("erreur");
					}
					else {
						input.classList.remove("erreur");
						confirmChangement(input.name, input.placeholder,
							input.value);
					}
				}
				else {
					confirmChangement(input.name, input.placeholder,
						input.value);
				}
			}
		});
	});
}

/**
 * Vérifier le format de l'adresse courriel
 *
 * @param {string} email à vérifier
 * @return {boolean} true si le format est valide, false sinon
 */
function verifFormatCourriel(email) {
	const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	return regex.test(email);
}

/**
 * Affiche la boîte de dialogue, en changeant le contenu du message et
 * mets en place les événements sur les boutons (envoi au backend si
 * confirmation)
 *
 * @param {string} nomChamp - le nom du champ
 * @param {string} valAvant - valeur du champ avant modification
 * @param {string} valApres - valeur du champ après modification
 */
function confirmChangement(nomChamp, valAvant, valApres) {
	let boite = document.getElementById("confirmChgmt");
	boite.style.display = "flex";

	let msg = document.getElementById("msg");
	if (nomChamp === "mdp") {
		msg.innerHTML = valAvant + " ➡️ " + valAvant;
	}
	else {
		msg.innerHTML = valAvant + " ➡️ " + valApres;
	}

	document.getElementById("confirmer").addEventListener("click", () => {
		// Envoi au backend
		envoiInfoCmptBack(nomChamp, valApres);
		boite.style.display = "none";
	});
	document.getElementById("annuler").addEventListener("click", () => {
		boite.style.display = "none";
	});
}

/**
 * Envoi le nom du champ et la valeur à enregistrer, au backend
 *
 * @param {string} nomChamp - le nom du champ
 * @param {string} valeur - à enregsitrer
 */
function envoiInfoCmptBack(nomChamp, valeur) {
	const xhr = new XMLHttpRequest();
	xhr.open("POST", "/backend/enregistrerInfoCmpt", true);
	xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	xhr.send(JSON.stringify({champ: nomChamp, valeur: valeur}));
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