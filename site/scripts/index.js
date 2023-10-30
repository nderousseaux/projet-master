const contIdButtons = [
	["selectChamp", "champSlct", true], ["selectCmpt", "cmptSlct", false],
	["selectType", "typeSlct", true], ["selectIlot", "ilotSlct", true]
];

// Activer les boutons des dropdowns
contIdButtons.forEach(element => {
	activerBouton(element[0], element[1], element[2]);
});

// [DEBUG] Bouton pour obtenir la valeur du champ et de l'ilot sélectionnés
let logoHeader = document.querySelector("header > img");
logoHeader.addEventListener("click", _ => {
	const valChamp = document.getElementById("champSlct").value;
	const valIlot = document.getElementById("ilotSlct").value;

	console.log("Champ :", valChamp, "\t| Ilot :", valIlot)
});