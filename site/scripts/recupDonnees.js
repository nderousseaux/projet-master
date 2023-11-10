/**
 * Récupère les moyennes de température, humidité et luminosité pour le champ
 * indiqué
 *
 * @param {int} numChamp - Numéro du champ
 * @returns {promise} - les données sous format json
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
					console.log(donnees); // [DEBUG] Pour Lucas
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
 * Récupère les mesures pour un ilot, pour une durée donnée et pour un type de
 * mesures
 *
 * @param {int} numChamp - Numéro du champ
 * @param {int} numIlot - Numéro de l'ilot
 * @param {string} typeMesures - Type de mesures à récupérer
 * @returns {promise} - les données sous format json
 **/
function recupMesuresIlot(numChamp, numIlot, typeMesures) {
	return new Promise((resolve, reject) => {
		// Champ à envoyer au back, pour indiquer la colonne à récupérer
		let champPost = new FormData();
		champPost.append("numChamp", numChamp);
		champPost.append("numIlot", numIlot);
		champPost.append("typeMesures", typeMesures);

		// Récupère les dates des mesures et les données de la colonne demandée
		fetch("../backend/recupMesuresIlot.php", {
			method: "POST",
			body: champPost
		})
		.then(reponse => {
			reponse.json()
				.then(donnees => {
					// console.log(donnees); // [DEBUG] Pour Lucas
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
 * Récupère les données de d'absisses et d'ordonnées pour le graphique
 *
 * @param {int} numChamp - Numéro du champ
 * @param {int} numIlot - Numéro de l'ilot
 * @param {string} duree - Durée d'affichage des données météo
 * @param {string} typeMesures - Type de mesures à récupérer
 * @returns {promise} - les données sous format json
 */
function recupAbsOrdGraph(numChamp, numIlot, duree, typeMesures) {
	return new Promise((resolve, reject) => {
		recupMesuresIlot(numChamp, numIlot, duree, typeMesures)
		.then(retour => {
			resolve(retour);
		})
		.catch(err => {
			reject(err);
		})
	})
}

/**
 * Récupère les données météo dans le back (pour masquer la clé API)
 *
 * @param {string} duree - Durée d'affichage des données météo
 * @returns {promise} - les données météo sous format json
 */
function recupMeteo(duree) {
	return new Promise((resolve, reject) => {
		// Champ à envoyer au back, pour indiquer la colonne à récupérer
		let champPost = new FormData();
		champPost.append("duree", duree);

		fetch("../backend/meteo.php", {
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