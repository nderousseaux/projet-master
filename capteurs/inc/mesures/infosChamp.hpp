#ifndef INFOS_CHAMP_H
#define INFOS_CHAMP_H

class InfosChamp {
	/* Variables */
	private:
		int idChamp_;
		int idIlot_;
		int idCapt_;


	/* Constructeur et destructeur */
	public:
		InfosChamp(void) = default;
		~InfosChamp(void) = default;


	/* Getters */
	public:
		inline int getIdChamp(void) const {
			return idChamp_;
		}
		inline int getIdIlot(void) const {
			return idIlot_;
		}
		inline int getIdCapt(void) const {
			return idCapt_;
		}


	/* Setters */
	public:
		inline void setInfosChamp(int idChamp, int idIlot, int idCapt) {
			this->idChamp_ = idChamp;
			this->idIlot_ = idIlot;
			this->idCapt_ = idCapt;
		}
};

#endif