/**
 * Activer les boutons d'un dropdown. Si un bouton est sélectionné, il est
 * marqué d'un ID unique, permettant de récupérer sa valeur facilement.
 *
 * @param {string} idContainer - ID du container du dropdown
 * @param {string} idAttr - ID à attribuer au bouton sélectionné
 * @param {bool} activerPreselect - Si true, sélectionne le premier bouton
 **/
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
				}
				else {
					btn.classList.remove("selected");
					btn.removeAttribute("id");
				}
			});
		});

		// Ajouter la classe "selected" et l'id avec comme valeur idAttr au
		// premier button, si aucun autre ne possède déjà la classe
		// Ne le fait pas pour le menu des actions sur le compte
		if (
			activerPreselect === true &&
			index === 0 &&
			!button.classList.contains("selected")
		) {
			button.classList.add("selected");
			button.setAttribute("id", idAttr);
		}
	});
}