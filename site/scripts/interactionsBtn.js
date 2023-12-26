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

					afficherTitreDropdown(idContainer);
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

			afficherTitreDropdown(idContainer);
		}
	});
}

/**
 * Modifie le texte adjacent au dropdown pour afficher la valeur sélectionnée
 * 
 * @param {string} idContainer - ID du container du dropdown
 */
function afficherTitreDropdown(idContainer) {
	// Titre du champ
	if (idContainer === "selectChamp") {
		afficherChampSelectionne();
	}
	// Titre de l'ilot
	else if (idContainer === "selectIlot") {
		afficherIlotSelectionne();
	}
	// Titre du type de mesure
	else if (idContainer === "selectType") {
		afficherMesureSelectionnee();
	}
}

/**
 * Affiche le nom du champ sélectionné dans le header
 */
function afficherChampSelectionne() {
	const container = document.querySelector("header > " +
		"section:nth-child(2) > p");
	container.textContent = "Champ " +
		document.getElementById("champSlct").value;
}

/**
 * Affiche le numéro de l'ilot sélectionné dans les options du graphique
 */
function afficherIlotSelectionne() {
	const container = document.querySelector("#optGraph > div:last-child > p");
	container.textContent = "Ilot " + document.getElementById("ilotSlct").value;
}

/**
 * Affiche le type de mesure sélectionné dans les options du graphique
 */
function afficherMesureSelectionnee() {
	const container = document.querySelector("#optGraph > div:first-child > p");
	container.textContent = document.getElementById("typeSlct").textContent;
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
			afficherMeteo(idUtilisateur);
			afficherMoyennes(idUtilisateur);
			afficherInfosChamp(idUtilisateur);
			afficherMesuresChamp(idUtilisateur);

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

// function exportCSV(idUtilisateur) {
// 	const valChamp = document.getElementById("champSlct").value;
// 	const valIlot = document.getElementById("ilotSlctExport").value;
// 	const valType = document.getElementById("typeSlctExport").value;value;

// }