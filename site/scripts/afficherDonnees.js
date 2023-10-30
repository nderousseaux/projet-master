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
		
		// Afficher les moyennes dans les paragraphes
		container.forEach((_, index) => {
			container[index].textContent = donnees[index];
		});
	});
}