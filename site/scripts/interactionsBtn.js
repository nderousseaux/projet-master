/**
 * Activer les boutons d'un dropdown. Si un bouton est sélectionné, il est
 * marqué d'un ID unique, permettant de récupérer sa valeur facilement.
 *
 * @param {string} idContainer - ID du container du dropdown
 * @param {string} idAttr - ID à attribuer au bouton sélectionné
 * @param {bool} activerPreselect - Si true, sélectionne le premier bouton
 */
function activerBouton(idContainer, idAttr, activerPreselect) {
	const dropdownContent = document.getElementById(idContainer);
	const buttons = dropdownContent.querySelectorAll("button");

	buttons.forEach((button, index) => {
		button.addEventListener("click", _ => {
			// Récupérer la valeur du bouton sélectionné
			const valeurSelectionnee = button.value;

			// Marquer le bouton comme sélectionné
			buttons.forEach(btn => {
				if (btn.value === valeurSelectionnee) {
					btn.classList.add("selected");
					btn.setAttribute("id", idAttr);

					/*
					 * Si le bouton sélectionné est celui du champ, afficher
					 * son nom dans le header
					 */
					if (idContainer === "selectChamp") {
						afficherNomChamp();
					}
				}
				else {
					btn.classList.remove("selected");
					btn.removeAttribute("id");
				}
			});
		});

		/*
		 * Ajouter la classe "selected" et l'id avec comme valeur idAttr au
		 * premier button, si aucun autre ne possède déjà la classe
		 */
		if (
			activerPreselect === true &&
			index === 0 &&
			!button.classList.contains("selected")
		) {
			button.classList.add("selected");
			button.setAttribute("id", idAttr);

			if (idContainer === "selectChamp") {
				afficherNomChamp();
			}
		}
	});
}

/**
 * Active la gestion de la mise à jour des ilots disponibles en fonction du
 * champ sélectionné.
 * Lance également la mise à jour du graphique pour le nouvel ilot sélectionné
 * par défaut.
 *
 * @param {int} idUtilisateur - ID de l'utilisateur
 */
function activerBoutonChgmtChamp(idUtilisateur) {
	const dropdownContent = document.getElementById(contIdButtons[0][0]);
	const buttons = dropdownContent.querySelectorAll("button");

	buttons.forEach(button => {
		button.addEventListener("click", _ => {
			afficherIlots(idUtilisateur)
			.then(_ => {
				// Active les boutons des ilots
				activerBouton(contIdButtons[3][0], contIdButtons[3][1],
					contIdButtons[3][2]);

				// Mets à jour le graphique
				helperAfficherGraph();
			});
		});
	});
}