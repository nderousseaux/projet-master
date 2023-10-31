/**
 * /!\ [NON TESTÉ] Pas de format de BDD de dispo, ni backend /!\
 *
 *  Affiche les moyennes de température, d'humidité et de luminosité pour le
 * champ indiqué
 *
 * @param {int} numChamp - Numéro du champ
 */
function afficherMoyTempHumiLumiChamp(numChamp) {
	recupMoyTmpHumiLumiChamp(numChamp).then(donnees => {
		const container = document.querySelectorAll("#secMoyennes > div > p");

		// Afficher les moyennes dans les sections correspondantes
		container.forEach((_, index) => {
			container[index].textContent = donnees[index];
		});
	});
}

/**
 * Affiche les données météo, récupérées dans le back
 */
function afficherMeteo() {
	const clesTemp = ["tempmin", "tempmax", "temp"];

	const meteoDiv = document.querySelector("#secMeteo > div");
	recupMeteo()
	.then(donnees => {
		if (donnees[0] != "Erreur") {
			const jours = donnees.days;

			jours.forEach(mesures => {
				for (const [cle, valeur] of Object.entries(mesures)) {
					let cellule = document.createElement("div");

					// Ajoute indication en fonction du type de précipitations
					if (cle === "preciptype") {
						cellule = cellulePrecip(valeur, cellule);
					}
					else {
						if (valeur === null) {
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
				};
			});
		}
		else {
			console.error("HTTP", donnees[1], ": récupération données météo");
		}
	})
	.catch(err => {
		console.error(err);
	})
}

/**
 * Traduit les valeurs de précipitations en français, et ajoute l'indication
 * visuelle dans la cellule
 *
 * @param {object} objPrecip objet contenant les valeurs de précipitations 
 * @param {div} cellule où afficher les précipitations
 * @returns la cellule modifiée
 */
function cellulePrecip(objPrecip, cellule) {
	const precipEnFr = [["rain", "Pluie 🌧️"], ["snow", "Neige ❄️"], ["freezingrain", "Pluie verglaçante 🌧️❄️"], ["ice", "Givre ❄️"]];
	if (objPrecip === null) {
		cellule.textContent += "-";
		return cellule;
	}
	for (const [_, valPrecip] of Object.entries(objPrecip)) {
		for (const [valEn, valFr] of precipEnFr) {
			if (valPrecip === valEn) {
				console.log(valPrecip, valEn)
				cellule.textContent += valFr;
			}
		}
	};

	return cellule;
}

/**
 * Ajoute un indicateur visuel de la direction du vent
 *
 * @param {int} dirVent en degrés
 * @param {div} cellule où afficher les précipitations
 * @returns la cellule modifiée
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
 * @param {*} temp en °C
 * @param {div} cellule où afficher les précipitations
 * @returns la cellule modifiée
 */
function celluleTemp(temp, cellule) {
	const gel = 0;
	const canicule = 30;

	if (temp <= gel) {
		cellule.textContent += " ❄️";
	}
	else if (temp >= canicule) {
		cellule.textContent += " 🔥";
	}

	return cellule;
}