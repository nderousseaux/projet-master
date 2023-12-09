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