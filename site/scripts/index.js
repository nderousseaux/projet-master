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

// API Météo
// Quota de 1000 requêtes par mois et à sécuriser
let cleAPI = "demanderALoïcSiBesoin";
fetch("https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/48.52854%2C7.711011?unitGroup=metric&elements=datetime%2Ctempmax%2Ctempmin%2Ctemp%2Chumidity%2Cprecip%2Cpreciptype%2Cwindspeedmean%2Cwinddir%2Ccloudcover%2Cuvindex&include=days&key=" + cleAPI + "&contentType=json", {
	"method": "GET",
	"headers": {
	}
})
.then(response => {
	return response.json();
})
.then(data => {
	const meteoDiv = document.getElementById("meteo");
	const jour = data.days[0].datetime;
	const tempMin = data.days[0].tempmin;
	const tempMax = data.days[0].tempmax;
	const temp = data.days[0].temp;
	const humi = data.days[0].humidity;
	const precip = data.days[0].precip;
	const precipType = data.days[0].preciptype[0];
	const windSpeed = data.days[0].windspeedmean;
	const windDir = data.days[0].winddir;
	const cloudcover = data.days[0].cloudcover;
	const uvIndex = data.days[0].uvindex;

	meteoDiv.innerHTML = `
		<p>Jour : ${jour}</p>
		<p>Température actuelle : ${temp}°C</p>
		<p>Température minimale : ${tempMin}°C</p>
		<p>Température maximale : ${tempMax}°C</p>
		<p>Humidité actuelle : ${humi}%</p>
		<p>Précipitations actuelles : ${precip} mm</p>
		<p>Type de précipitations : ${precipType}</p>
		<p>Vitesse du vent actuelle : ${windSpeed} km/h</p>
		<p>Direction du vent actuelle : ${windDir}°</p>
		<p>Couverture nuageuse actuelle : ${cloudcover}%</p>
		<p>Indice UV actuel : ${uvIndex}</p>
	`;
})
.catch(err => {
	console.error(err);
});