
/**
 * Récupère les données météo dans le back (pour masquer la clé API)
 *
 * @returns {promise} - les données météo sous format json
 */
function recupMeteo() {
	return new Promise((resolve, reject) => {
		fetch("../backend/meteo.php")
		.then(reponse => {
			reponse.json()
				.then(donnees => {
					// console.log(JSON.stringify(donnees));
					resolve(donnees);
				})
				.catch(err => {
					reject(err);
				})
		})
		.catch(err => {
			reject(err);
		})
	})
}

/**
 * Affiche les données météo, récupérées dans le back
 */
function afficherMeteo() {
	const meteoDiv = document.querySelector("#secMeteo > div");
	recupMeteo()
	.then(donnees => {
		if (donnees[0] != "Erreur") {
			const jours = donnees.days;

			jours.forEach(mesures => {
				for (const [_, valeur] of Object.entries(mesures)) {
					let cellule = document.createElement("div");

					if (valeur === null) {
						cellule.textContent = "N/A";
					}
					else {
						cellule.textContent = valeur;
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