/**
 * Classe pour les couleurs du graphique
 */
class CouleursGraph {
	constructor(bgColor, gridColor, color, lineColor, fillColor) {
		this.bgColor = bgColor;
		this.gridColor = gridColor;
		this.color = color;
		this.lineColor = lineColor;
		this.fillColor = fillColor;
	}

	/**
	 * Récupère les couleurs du graphique
	 *
	 * @returns {array} - Un tableau contenant les couleurs du graphique
	 *					  (background, grid, font, line, fill)
	 */
	getCouleursTableau() {
		return Array(
			this.bgColor, this.gridColor,
			this.color, this.lineColor,
			this.fillColor
		);
	}

	/**
	 * Récupère les couleurs des données du graphique
	 * 
	 * @returns {array} - Un tableau contenant les couleurs des données du
	 * 					  graphique (line, fill)
	 */
	getCouleursData() {
		return Array(
			this.lineColor, this.fillColor
		);
	}

	/**
	 * Récupère les couleurs du layout du graphique
	 * 
	 * @returns {array} - Un tableau contenant les couleurs du layout du
	 * 					  graphique (background, grid, font)
	 */
	getCouleursLayout() {
		return Array(
			this.bgColor, this.gridColor, this.color
		);
	}
}

/**
 * Classe pour les couleurs claires du graphique
 */
class CouleursClaires extends CouleursGraph {
	constructor() {
		super(
			"#f9f8fa", "#eeeeee",
			"#404040", "#e7e7e7",
			"rgba(205, 205, 205, 0.2)"
		);
	}
}

/**
 * Classe pour les couleurs sombres du graphique
 */
class CouleursSombres extends CouleursGraph {
	constructor() {
		super(
			"#0e0e0f", "#494949",
			"#bfbfbf", "#4f4f4f",
			"rgba(25, 25, 25, 0.8)"
		);
	}
}

/**
 * Classe pour les couleurs des données
 */
class CouleursDonnees {
	constructor(
		degrade1, degrade2, degrade3, degrade4,
		pourcentage1, pourcentage2, pourcentage3, pourcentage4
	) {
		this.degrade1 = degrade1;
		this.degrade2 = degrade2;
		this.degrade3 = degrade3;
		this.degrade4 = degrade4;

		this.pourcentage1 = pourcentage1;
		this.pourcentage2 = pourcentage2;
		this.pourcentage3 = pourcentage3;
		this.pourcentage4 = pourcentage4;
	}

	getPairesPourcentCouleur() {
		return Array(
			Array(this.pourcentage1, this.degrade1),
			Array(this.pourcentage2, this.degrade2),
			Array(this.pourcentage3, this.degrade3),
			Array(this.pourcentage4, this.degrade4)
		);
	}
}

/**
 * Classe pour les couleurs des données de température
 */
class CouleursDonneesTemp extends CouleursDonnees {
	constructor() {
		super(
			"#395d7f", "#00bfff", "#00dd00", "#d2691e",
			0.00, 0.35, 0.55, 1.00
		);
	}
}

/**
 * Classe pour les couleurs des données d'humidité
 */
class CouleursDonneesHum extends CouleursDonnees {
	constructor() {
		super(
			"#d2691e", "#00dd00", "#00bfff", "#395d7f",
			0.00, 0.45, 0.75, 1.00
		);
	}
}

/**
 * Classe pour les couleurs des données de luminosité
 */
class CouleursDonneesLumi extends CouleursDonnees {
	constructor() {
		super(
			"#0066cc", "#00aaff", "#ffcc00", "#ff5050",
			0.00, 0.03, 0.20, 1.00
		);
	}
}