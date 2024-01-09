/**
 * Affiche les champs de l'utilisateur
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 * @returns {promise} - résolue quand les champs sont affichés
 */
function afficherChamps(idUtilisateur) {
	let champPost = new FormData();
	champPost.append("idUtilisateur", idUtilisateur);

	return new Promise((resolve, reject) => {
		recupDonnees(champPost, "recupNumChamps.php")
		.then(donnees => {
			const container = document.getElementById("selectChamp");

			for (let i = 1; i <= donnees; i++) {
				const champ = document.createElement("button");
				champ.setAttribute("value", i);
				champ.textContent = "Champ " + i;

				container.appendChild(champ);
			};
			container.classList.remove("ddHeader");

			resolve();
		})
		.catch(err => {
			reject(err);
		});
	});
}

/**
 * Affiche le nom de l'utilisateur
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 */
function afficherNomUtilisateur(nomUtilisateur, idUtilisateur) {
	const container = document.querySelector("header > section:last-child > p");
	container.textContent = nomUtilisateur + " #" + idUtilisateur;
}

/**
 * Affiche les infos du champ sélectionné
 *
 * @param {array} donnees - contient les infos du champ :
 * 							-> état du champ (0, 1, 2 ou 3),
 * 							-> nombre de capteurs actifs,
 * 							-> nombre de capteurs total,
 * 							-> date de dernière mise à jour.
 */
function afficherInfosChamp(donnees) {
	// État général du champ
	const containerEtatChamp = document.querySelector("#secInfos > " +
		"section > div:first-child");
	const etatChamp = document.querySelector("#secInfos > section > " +
		"div:first-child > p");

		// Ajoute un indicateur visuel en fonction de l'état du champ
	if (donnees[0] === 0) {
		etatChamp.textContent = "Parfait";
		containerEtatChamp.className = '';
	}
	else if (donnees[0] === 1) {
		etatChamp.textContent = "Acceptable";
		containerEtatChamp.classList.add("etatAcceptable");
	}
	else if (donnees[0] === 2) {
		etatChamp.textContent = "Dégradé";
		containerEtatChamp.classList.add("etatDegrade");
	}
	else if (donnees[0] === 3) {
		etatChamp.textContent = "Hors service";
		containerEtatChamp.classList.add("etatHorsService");
	}

	// Nombre de capteurs actifs
	document.querySelector("#nbrCapteurs > p:first-child")
		.textContent = donnees[1];

	// Nombre de capteurs total
	document.querySelector("#nbrCapteurs > p:last-child")
		.textContent = donnees[2];

	// Dernière mise à jour
	document.querySelector("#secInfos > section > div:last-child > p")
		.textContent = donnees[3];
}

/**
 * Affiche les moyennes de température, d'humidité et de luminosité pour le
 * champ indiqué
 *
 * @param {array} donnees - contient les moyennes de température, d'humidité et
 * 							de luminosité pour le champ indiqué
 */
function afficherMoyennes(donnees) {
	const cellTemp = document.querySelector("#secMoyennes > section > " +
		"div:first-child > p");
	const cellHumi = document.querySelector("#secMoyennes > section > " +
		"div:nth-child(2) > p");
	const cellLumi = document.querySelector("#secMoyennes > section > " +
		"div:last-child > p");

	cellTemp.textContent = donnees[0] + "°C";
	cellHumi.textContent = donnees[1] + "%";
	cellLumi.textContent = donnees[2] + " lux";
}

/**
 * Affiche toutes les mesures pour un champ indiqué
 *
 * @param {array} donnees - contient toutes les mesures pour le champ indiqué
 */
function afficherMesuresChamp(donnees) {
	viderTableau("donneesTableau");
	const container = document.getElementById("donneesTableau");

	donnees.forEach(element => {
		const cellule = document.createElement("div");
		cellule.classList.add("cellule");

		// Capteur défectueux
		if (element === "C1") {
			cellule.classList.add("errMesure");
			cellule.textContent = "⚠️ Capteur défectueux";
		}
		// Raspberry Pi défectueux
		else if (element === "C2") {
			cellule.classList.add("errMesure");
			cellule.textContent = "⚠️ Raspberry Pi défectueux";
		}
		// Aucun problème
		else {
			cellule.textContent = "OK";
		}

		container.appendChild(cellule);
	});
}

/**
 * Affiche les ilots du champ sélectionné
 *
 * @param {int} nbrIlots - Nombre d'ilots du champ sélectionné
 */
function afficherIlots(nbrIlots) {
	// Récupère les containers des dropdown des ilots
	const containers = document.getElementsByClassName("ilot");

	// Itère sur les containers
	Array.prototype.forEach.call(containers, container => {
		// Supprimer les ilots déjà affichés (en cas de changement de champ)
		while (container.firstChild) {
			container.removeChild(container.firstChild);
		}

		// Ajoute le bouton "Tous les ilots" dans le dropdown de l'export
		if (container.id === "selectIlotExport") {
			const ilot = document.createElement("button");
			ilot.setAttribute("value", "tous");
			ilot.textContent = "Tous";

			container.appendChild(ilot);
		}

		for (let i = 1; i <= nbrIlots; i++) {
			const ilot = document.createElement("button");
			ilot.setAttribute("value", i);
			ilot.textContent = "Ilot " + i;

			container.appendChild(ilot);
		};
	});
}

/**
 * Affiche les données météo, récupérées dans le back
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 * @returns {bool} - false si la durée est invalide
 */
function afficherMeteo(idUtilisateur) {
	const duree = document.getElementById("dureeSlct").value;
	if (duree != "jour" && duree != "semaine") {
		console.error("Durée invalide");
		return false;
	}

	// Types de températures possibles
	const clesTemp = ["tempmin", "tempmax", "temp"];

	// Titres des cellules (dans l'ordre affiché)
	const ordreCles = [
		"datetime", "tempmax", "tempmin", "temp", "humidity", "precip",
		"preciptype", "winddir", "cloudcover", "uvindex", "windspeedmean"
	]

	const numChamp = document.getElementById("champSlct").value - 1;

	let champPost = new FormData();
	champPost.append("idUtilisateur", idUtilisateur);
	champPost.append("numChamp", numChamp);
	champPost.append("duree", duree);

	recupDonnees(champPost, "recupMeteo.php")
	.then(donnees => {
		if (donnees[0] != "Erreur") {
			// Supprimer les données déjà affichées
			viderTableau("donneesMeteo");

			// Adapter l'affichage en fonction de la durée
			let dureeDonnees;
			if (duree === "jour") {
				dureeDonnees = donnees.days[0].hours;
			}
			else if (duree === "semaine") {
				dureeDonnees = donnees.days;
			}

			dureeDonnees.forEach(mesures => {
				const meteoDiv = document.querySelector("#donneesMeteo");
				let nbrColonnes = 11;
				let index = 0;

				for (let [cle, valeur] of Object.entries(mesures)) {
					let cellule = document.createElement("div");

					/*
					 * Ajoute indication en fonction du type de précipitations
					 * et traduit en français la valeur (ex: "rain" -> "Pluie")
					 */
					if (cle === "preciptype") {
						cellule = cellulePrecip(valeur, cellule);
					}
					else {
						// Si la clé n'est pas au bon index, décale les cellules
						if (
							ordreCles[index] != cle &&
							ordreCles.includes(cle)
						) {
							const lenOrdreCles = Math.max(index,
								ordreCles.length);

							for (; index < lenOrdreCles; index++) {
								// Si la clé est au bon index, afficge la valeur
								if (ordreCles[index] === cle) {
									cellule.textContent = valeur;
									break;
								}

								// Sinon affiche "N/A"
								else {
									const cellule = ajoutCellule("N/A");
									meteoDiv.appendChild(cellule);
									nbrColonnes--;
								}
							}
						}
						else if (valeur === null) {
							cellule.textContent = "N/A";
						}
						else {
							cellule.textContent = valeur;
						}
					}

					// Ajoute indication en fonction de la température
					if (clesTemp.includes(cle)) {
						celluleTemp(valeur, cellule);
					}

					// Ajoute indication en fonction de la direction du vent
					if (cle === "winddir") {
						celluleDirVent(valeur, cellule);
					}

					cellule.classList.add("cellule");
					meteoDiv.appendChild(cellule);
					index++;
					nbrColonnes--;
				};

				// S'il manque des données, affiche "N/A"
				for (let i = 0; i < nbrColonnes; i++) {
					const cellule = ajoutCellule("N/A");
					meteoDiv.appendChild(cellule);
				}
			});
		}
		else {
			console.error("Récupération données météo : " + donnees[1] + " > " +
				"\"" + duree + "\"");
		}
	})
	.catch(err => {
		console.error(err);
	});
}

/**
 * Supprime toutes les données d'un tableau, sauf la ligne de titre
 *
 * @param {string} idTableau - id du tableau
 */
function viderTableau(idTableau) {
	const container = document.getElementById(idTableau);
	const cellules = container.querySelectorAll(".cellule:not(.titre)");
	cellules.forEach(cellule => cellule.remove());
}

/**
 * Créé une cellule et y ajoute le texte passé en paramètre
 *
 * @param {string} texte - à afficher dans la cellule
 * @returns {div} - la cellule créée
 */
function ajoutCellule(texte) {
	const cellule = document.createElement("div");
	cellule.classList.add("cellule");
	cellule.textContent = texte;
	return cellule;
}

/**
 * Traduit les valeurs de précipitations en français, et ajoute l'indication
 * visuelle dans la cellule
 *
 * @param {object} objPrecip - objet contenant les valeurs de précipitations
 * @param {div} cellule - où afficher les précipitations
 * @returns {div} - la cellule modifiée
 */
function cellulePrecip(objPrecip, cellule) {
	const precipEnVersFr = [
		["rain", "Pluie 🌧️\r\n"], ["snow", "Neige ❄️\r\n"],
		["freezingrain", "Pl. vergla. 🌧️❄️\r\n"], ["ice", "Givre ❄️\r\n"]
	];

	if (objPrecip === null) {
		cellule.textContent = "-";
		return cellule;
	}

	for (const [_, valPrecip] of Object.entries(objPrecip)) {
		for (const [valEn, valFr] of precipEnVersFr) {
			if (valPrecip === valEn) {
				const intraCellule = document.createElement("p");
				intraCellule.textContent = valFr;
				cellule.appendChild(intraCellule);
			}
		}
	};

	return cellule;
}

/**
 * Ajoute un indicateur visuel de la direction du vent
 *
 * @param {float} dirVent - en degrés
 * @param {div} cellule - où afficher les précipitations
 * @returns {div} - la cellule modifiée
 */
function celluleDirVent(dirVent, cellule) {
	if (dirVent >= 0 && dirVent <= 45) {
		cellule.textContent += " ↗️";
	}
	else if (dirVent > 45 && dirVent <= 90) {
		cellule.textContent += " ➡️";
	}
	else if (dirVent > 90 && dirVent <= 135) {
		cellule.textContent += " ↘️";
	}
	else if (dirVent > 135 && dirVent <= 180) {
		cellule.textContent += " ⬇️";
	}
	else if (dirVent > 180 && dirVent <= 225) {
		cellule.textContent += " ↙️";
	}
	else if (dirVent > 225 && dirVent <= 270) {
		cellule.textContent += " ⬅️";
	}
	else if (dirVent > 270 && dirVent <= 315) {
		cellule.textContent += " ↖️";
	}
	else {
		cellule.textContent += " ⬆️";
	}

	return cellule;
}

/**
 * Ajoute un indicateur visuel en fonction de la température, en cas de risque
 * de gel ou de canicule
 *
 * @param {float} temp - en °C
 * @param {div} cellule - où afficher les précipitations
 * @returns {div} - la cellule modifiée
 */
function celluleTemp(temp, cellule) {
	const gel = 0.0;
	const canicule = 30.0;

	if (temp <= gel) {
		cellule.textContent += " ❄️";
	}
	else if (temp >= canicule) {
		cellule.textContent += " ☀️";
	}

	return cellule;
}

/**
 * Affiche le nom de l'utilisateur, les informations du champ, les moyennes,
 * les mesures et les ilots disponibles
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 * @returns {promise} - résolue quand les données sont affichées
 */
function helperAffichageDonneesChamp(idUtilisateur) {
	return new Promise((resolve) => {
		const numChamp = document.getElementById("champSlct").value - 1;

		let champPost = new FormData();
		champPost.append("idUtilisateur", idUtilisateur);
		champPost.append("numChamp", numChamp);

		recupDonnees(champPost, "recupDonneesAgri.php")
		.then(donnees => {
			afficherNomUtilisateur(donnees[0], idUtilisateur);
			afficherInfosChamp(donnees[1]);
			afficherMoyennes(donnees[2]);
			afficherMesuresChamp(donnees[3]);
			afficherIlots(donnees[4]);

			resolve();
		})
		.catch(err => {
			console.error(err);
		});
	});
}

/**
 * Affiche les infos de l'utilisateur dans le formulaire
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 * @param {bool} requeteAdmin - true si la requête est faite par un admin
 * 								dans ce cas, renvoi le rôle de l'utilisateur en
 * 								dernier dans la réponse
 */
function afficherDonneesUtilisateur(idUtilisateur, requeteAdmin = false) {
	let champPost = new FormData();
	champPost.append("idUtilisateur", idUtilisateur);
	champPost.append("requeteAdmin", requeteAdmin);

	recupDonnees(champPost, "recupInfosUtilisateur.php")
	.then(donnees => {
		const prenomInput = document.getElementById("prenom");
		const nomInput = document.getElementById("nom");
		const courrielInput = document.getElementById("courriel");
		const mdp = document.getElementById("mdp");
		const couleur1 = document.getElementById("couleur1");
		const couleur2 = document.getElementById("couleur2");
		const icone = document.querySelector("#icone > div");

		// Si requête réalisée par un admin, affiche le rôle de l'utilisateur
		let roleSelect;
		if (requeteAdmin) {
			roleSelect = document.getElementById("role");
		}

		prenomInput.value = donnees[0];
		prenomInput.placeholder = donnees[0];
		nomInput.value = donnees[1];
		nomInput.placeholder = donnees[1];
		courrielInput.value = donnees[2];
		courrielInput.placeholder = donnees[2];
		mdp.value = '';
		mdp.placeholder = "******";
		couleur1.value = donnees[3];
		couleur1.placeholder = donnees[3];
		couleur2.value = donnees[4];
		couleur2.placeholder = donnees[4];

		if (requeteAdmin) {
			// Vérifie que le rôle est valide
			let option;
			if (donnees[5] === "admin" || donnees[5] === "standard") {
				roleSelect.value = donnees[5];
				option = document.querySelector("#role > option[value=" +
					donnees[5] +"]");
			}
			// Sinon, par défaut met le rôle à "standard"
			else {
				roleSelect.value = "standard";
				option = document.querySelector("#role > option[value=" +
					"standard]");
				console.error("Rôle invalide : " + donnees[5]);
			}
			option.id = "selectionne";
		}

		icone.innerHTML = prenom[0] + ". " + nom[0] + '.';
	})
	.catch(err => {
		console.error(err);
	});
}

/**
 * Affiche tous les utilisateurs dans le menu déroulant
 *
 * @returns {promise} - résolue quand les utilisateurs sont affichés
 */
function afficherUtilisateurs() {
	return new Promise((resolve) => {
		let champPost = new FormData();

		recupDonnees(champPost, "recupUtilisateurs.php")
		.then(donnees => {
			const container = document.getElementById("selectUtilisateur");

			for (let i = 0; i < donnees.length; i++) {
				const utilisateur = document.createElement("button");
				utilisateur.setAttribute("value", donnees[i][0]);
				utilisateur.textContent = donnees[i][1] + ' ' + donnees[i][2];

				container.appendChild(utilisateur);
			};
			container.classList.remove("ddHeader");

			resolve();
		})
		.catch(err => {
			console.error(err);
		});
	});
}