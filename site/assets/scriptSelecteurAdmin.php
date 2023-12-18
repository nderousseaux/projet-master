/*** Interaction des boutons ***/
	// Active les boutons du dropdown
activerBouton("selectUtilisateur", "utilisateurSlct", true);

	// Gère le changement d'utilisateur dans le formulaire
let dropdownUtilisateur = document.getElementById("ddUtilisateur");
dropdownUtilisateur.addEventListener("click", _ => {
	idUtilisateur = document.getElementById("utilisateurSlct").value;
	afficherDonneesUtilisateur(idUtilisateur);
});


/*** Affichage des données ***/
	// Affiche les utilisateurs de la coopérative agricole
afficherUtilisateurs()
.then(_ => {
	activerBouton("selectUtilisateur", "utilisateurSlct", true);
});