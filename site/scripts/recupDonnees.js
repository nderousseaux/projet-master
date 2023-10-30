/**
 * Récupère les moyennes de température, humidité et luminosité pour le champ
 * indiqué
 *
 * @param {int} numChamp - Numéro du champ
 **/
function recupMoyTmpHumiLumiChamp(numChamp) {
	return new Promise((resolve, reject) => {
		// Champ à envoyer au back, pour indiquer la colonne à récupérer
		let champPost = new FormData();
		champPost.append("numChamp", numChamp);

		// Récupère les dates des mesures et les données de la colonne demandée
		fetch("../backend/recupMoyennes.php", {
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
		})
	})
}