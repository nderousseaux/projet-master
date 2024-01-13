# arreter conteneur existant
echo "arret du conteneur . . ."
docker stop site_web

# créé le réseau si pas encore fait par mongo
docker network create -d bridge mongo-net

# build le conteneur si besoin
DIR="$(dirname "${0}")"
docker build -t site:latest $DIR

# run le conteneur
docker run --rm -p 8080:80 -d --name site_web \
	-e MONGODB_URL=mongodb://mongo1:30001 \
	--network=mongo-net \
	site:latest
