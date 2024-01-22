/**
 * Récupère des données dans le backend, en envoyant en POST des données et en
 * accédant à un fichier spécifique dans le backend
 *
 * @param {formData} champPost - À envoyer au backend
 * @param {string} fichierBackend - Fichier à appeler dans le backend
 * @returns {promise} - Promesse contenant les données récupérées
 */
function recupDonnees(champPost, fichierBackend) {
	return new Promise((resolve, reject) => {
		fetch("../backend/" + fichierBackend, {
			method: "POST",
			body: champPost
		})
		.then(reponse => {
			reponse.json()
				.then(retour => {
					resolve(retour);
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