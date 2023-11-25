/**
 * Récupère des données dans le back, en fonction du champPost
 *
 * @param {int} champPost - Champ à envoyer au back
 * @param {string} urlBackend - URL du backend
 * @returns {promise} - les données sous format json
 */
function recupDonnees(champPost, fichierBackend) {
	return new Promise((resolve, reject) => {
		// Récupère les dates des mesures et les données de la colonne demandée
		fetch("../backend/" + fichierBackend, {
			method: "POST",
			body: champPost
		})
		.then(reponse => {
			reponse.json()
				.then(donnees => {
					resolve(donnees);
				})
				.catch(err => {
					reject(err);
				})
		})
		.catch(err => {
			reject(err);
		});
	});
}

/**
 * Récupère les données de d'absisses et d'ordonnées pour le graphique
 *
 * @param {FormData} champPost - Champ à envoyer au back
 * @param {string} fichierBackend - Fichier à appeler dans le backend
 * @returns {promise} - les données sous format json
 */
function recupAbsOrdGraph(champPost, fichierBackend) {
	return new Promise((resolve, reject) => {
		recupDonnees(champPost, fichierBackend)
		.then(retour => {
			resolve(retour);
		})
		.catch(err => {
			reject(err);
		});
	});
}