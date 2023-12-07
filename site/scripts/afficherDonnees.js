/**
 * Affiche le nom de l'utilisateur
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 */
function afficherNomUtilisateur(idUtilisateur) {
	let champPost = new FormData();
	champPost.append("idUtilisateur", idUtilisateur);

	recupDonnees(champPost, "recupNomUtilisateur.php")
	.then(donnees => {
		const nomUtilisateur = document.querySelector("header > " +
			"section:last-child > p")
		nomUtilisateur.textContent = donnees;
	});
}

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
 * Affiche les ilots du champ sélectionné
 * 
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 * @returns {promise} - résolue quand les ilots sont affichés
 */
function afficherIlots(idUtilisateur) {
	const numChamp = document.getElementById("champSlct").value - 1;

	let champPost = new FormData();
	champPost.append("idUtilisateur", idUtilisateur);
	champPost.append("numChamp", numChamp);

	return new Promise((resolve, reject) => {

		recupDonnees(champPost, "recupNumIlots.php")
		.then(donnees => {
			const container = document.getElementById("selectIlot");

			// Supprimer les ilots déjà affichés (en cas de changement de champ)
			while (container.firstChild) {
				container.removeChild(container.firstChild);
			}

			for (let i = 1; i <= donnees; i++) {
				const ilot = document.createElement("button");
				ilot.setAttribute("value", i);
				ilot.textContent = "Ilot " + i;

				container.appendChild(ilot);
			};
			resolve();
		})
		.catch(err => {
			reject(err);
		});
	});
}

/**
 * Affiche les infos du champ sélectionné
 * 
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 */
function afficherInfosChamp(idUtilisateur) {
	const numChamp = document.getElementById("champSlct").value - 1;

	let champPost = new FormData();
	champPost.append("numChamp", numChamp);
	champPost.append("idUtilisateur", idUtilisateur);

	recupDonnees(champPost, "recupInfosChamp.php")
	.then(donnees => {
		// État général du champ
		document.querySelector("#secInfos > div:first-child > p")
			.textContent = donnees[0];

		// Nombre de capteurs actifs
		document.querySelector("#nbrCapteurs > p:first-child")
			.textContent = donnees[1];
		
		// Nombre de capteurs total
		document.querySelector("#nbrCapteurs > p:last-child")
			.textContent = donnees[2];

		// Dernière mise à jour
		document.querySelector("#secInfos > div:last-child > p")
			.textContent = donnees[3];
	});
}

/**
 * Affiche les moyennes de température, d'humidité et de luminosité pour le
 * champ indiqué
 * 
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 */
function afficherMoyennes(idUtilisateur) {
	const numChamp = document.getElementById("champSlct").value - 1;

	let champPost = new FormData();
	champPost.append("numChamp", numChamp);
	champPost.append("idUtilisateur", idUtilisateur);

	recupDonnees(champPost, "recupMoyennes.php")
	.then(donnees => {
		const cellTemp = document.querySelector("#secMoyennes > " +
			"div:first-child > p");
		const cellHumi = document.querySelector("#secMoyennes > " +
			"div:nth-child(2) > p");
		const cellLumi = document.querySelector("#secMoyennes > " +
			"div:last-child > p");

		cellTemp.textContent = donnees[0] + "°C";
		cellHumi.textContent = donnees[1] + "%";
		cellLumi.textContent = donnees[2] + " lux";
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
 * Affiche toutes les mesures pour un champ indiqué
 * 
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 */
function afficherMesuresChamp(idUtilisateur) {
	const numChamp = document.getElementById("champSlct").value - 1;

	let champPost = new FormData();
	champPost.append("numChamp", numChamp);
	champPost.append("idUtilisateur", idUtilisateur);

	recupDonnees(champPost, "recupMesuresChamp.php")
	.then(retour => {
		viderTableau("donneesTableau");
		const container = document.getElementById("donneesTableau");

		retour.forEach(donnees => {
			donnees.forEach(element => {
				const cellule = document.createElement("div");
				cellule.classList.add("cellule");

				if (element === "KO") {
					cellule.classList.add("errMesure");
					cellule.textContent = "⚠️ ";
				}

				cellule.textContent += element;

				container.appendChild(cellule);
			});
		});
	});
}

/**
 * Supprime toutes les données d'un tableau, sauf la ligne de titre
 * 
 * @param {string} id - id du tableau
 */
function viderTableau(id) {
	const container = document.getElementById(id);
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
 * Affiche le nom du champ sélectionné dans le header
 */
function afficherNomChamp() {
	const container = document.querySelector("header > " +
		"section:nth-child(2) > p");
	container.textContent = "Champ " +
		document.getElementById("champSlct").value;
}