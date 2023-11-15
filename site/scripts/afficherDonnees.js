/**
 * Supprime toutes les données sauf la colonne de titre
 */
function supprimerMeteo() {
	const meteoDiv = document.getElementById("donneesMeteo");
	const colonnes = meteoDiv.querySelectorAll(".colonne:not(.titre)");
	colonnes.forEach(colonne => colonne.remove());
}

/**
 * Affiche les données météo, récupérées dans le back
 *
 * @param {string} duree - Durée d'affichage des données météo
 * @returns {bool} - false si la durée est invalide
 */
function afficherMeteo(duree) {
	if (duree != "jour" && duree != "semaine") {
		console.error("Durée invalide");
		return false;
	}

	// Types de températures possibles
	const clesTemp = ["tempmin", "tempmax", "temp"];

	// Titres des colonnes (dans l'ordre affiché)
	const ordreCles = [
		"datetime", "tempmax", "tempmin", "temp", "humidity", "precip",
		"preciptype", "winddir", "cloudcover", "uvindex", "windspeedmean"
	]

	let champPost = new FormData();
	champPost.append("duree", duree);

	recupDonnees(champPost, "recupMeteo.php")
	.then(donnees => {
		if (donnees[0] != "Erreur") {
			// Supprimer les données déjà affichées
			supprimerMeteo();

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

					cellule.classList.add("colonne");
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
 * Créé une cellule et y ajoute le texte passé en paramètre
 *
 * @param {string} texte - à afficher dans la cellule
 * @returns {div} - la cellule créée
 */
function ajoutCellule(texte) {
	const cellule = document.createElement("div");
	cellule.classList.add("colonne");
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
		["rain", "Pluie 🌧️"], ["snow", "Neige ❄️"],
		["freezingrain", "Pluie verglaçante 🌧️❄️"], ["ice", "Givre ❄️"]
	];

	if (objPrecip === null) {
		cellule.textContent += "-";
		return cellule;
	}
	for (const [_, valPrecip] of Object.entries(objPrecip)) {
		for (const [valEn, valFr] of precipEnVersFr) {
			if (valPrecip === valEn) {
				cellule.textContent += valFr;
			}
		}
	};

	return cellule;
}

/**
 * Ajoute un indicateur visuel de la direction du vent
 *
 * @param {int} dirVent - en degrés
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
		cellule.textContent += " ↖";
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
 * @param {int} temp - en °C
 * @param {div} cellule - où afficher les précipitations
 * @returns {div} - la cellule modifiée
 */
function celluleTemp(temp, cellule) {
	const gel = 0;
	const canicule = 30;

	if (temp <= gel) {
		cellule.textContent += " ❄️";
	}
	else if (temp >= canicule) {
		cellule.textContent += " ☀️";
	}

	return cellule;
}

/**
 * Affiche les champs de l'utilisateur
 *
 * @param {int} idUtilisateur - Numéro identifiant l'utilisateur
 */
function afficherChamps(idUtilisateur) {
	let champPost = new FormData();
	champPost.append("idUtilisateur", idUtilisateur);

	recupDonnees(champPost, "recupNumChamps.php")
	.then(donnees => {
		const container = document.getElementById("selectChamp");

		let index = 0;
		donnees.forEach(numChamp => {
			const champ = document.createElement("button");
			champ.setAttribute("value", numChamp);
			champ.textContent = "Champ " + numChamp;

			if (index === 0) {
				champ.classList.add("selected");
				index++;
			}

			container.appendChild(champ);
		});
	});
}

/**
 * Affiche les ilots du champ sélectionné
 *
 * @param {int} numChamp - Numéro du champ
 */
function afficherIlots(numChamp) {
	let champPost = new FormData();
	champPost.append("numChamp", numChamp);

	recupDonnees(champPost, "recupNumIlots.php")
	.then(donnees => {
		const container = document.getElementById("selectIlot");

		let index = 0;
		donnees.forEach(numIlot => {
			const ilot = document.createElement("button");
			ilot.setAttribute("value", numIlot);
			ilot.textContent = "Ilot " + numIlot;

			if (index === 0) {
				ilot.classList.add("selected");
				index++;
			}

			container.appendChild(ilot);
		});
	});
}

/**
 * Affiche toutes les mesures pour un champ indiqué
 *
 * @param {int} numChamp - Numéro du champ
 */
function afficherTableauToutesMesures(numChamp) {
	let champPost = new FormData();
	champPost.append("numChamp", numChamp);

	recupDonnees(champPost, "recupMesuresChamp.php")
	.then(donnees => {
		const container = document.querySelector("#secTableau > div");

		donnees.forEach(mesure => {
			const cellule = document.createElement("div");
			cellule.classList.add("colonne");
			cellule.textContent = mesure;
			container.appendChild(cellule);
		});
	});
}

/**
 * Affiche les moyennes de température, d'humidité et de luminosité pour le
 * champ indiqué
 *
 * @param {int} numChamp - Numéro du champ
 */
function afficherMoyennes(numChamp) {
	let champPost = new FormData();
	champPost.append("numChamp", numChamp);

	recupDonnees(champPost, "recupMoyennes.php")
	.then(donnees => {
		const cellTemp = document.querySelector("#secMoyennes > div > " +
			"p:first-child");
		const cellHumi = document.querySelector("#secMoyennes > div > " +
			"p:nth-child(2)");
		const cellLumi = document.querySelector("#secMoyennes > div > " +
			"p:last-child");

		cellTemp.textContent = donnees[0] + "°C";
		cellHumi.textContent = donnees[1] + "%";
		cellLumi.textContent = donnees[2] + " lux";
	});
}

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
 * Affiche les infos du champ sélectionné
 *
 * @param {int} numChamp - Numéro du champ
 */
function afficherInfosChamp(numChamp) {
	let champPost = new FormData();
	champPost.append("numChamp", numChamp);

	/*
	 * Récupère :
	 * - Date la plus récente de mesure d'un capteur (récente)
	 * - Date la plus récente de mesure d'un capteur (ancienne)
	 *   -> Permet de faire la différence entre les deux et d'afficher l'état
	 * - Nombre de capteurs
	 */
	recupDonnees(champPost, "recupInfosChamp.php")
	.then(donnees => {
		// État général du champ
		if (donnees[0] - donnees[1] < 1) {
			document.querySelector("#secInfos > div:first-child > p")
				.textContent = "OK";
		}
		else {
			document.querySelector("#secInfos > div:first-child > p")
				.textContent = "Err";
		}

		// Nombre de capteurs
		document.querySelector("#secInfos > div:nth-child(2) > p")
			.textContent = donnees[2];

		// Dernière mise à jour
		document.querySelector("#secInfos > div:last-child > p")
			.textContent = donnees[0];
	});
}