# Ajoute tous les fichiers .cpp dans le dossier src
file(GLOB SOURCES "${PROJECT_SOURCE_DIR}/src/*.cpp")

# Défini le standard à appliquer
set(CMAKE_CXX_STANDARD 17)

# Ajoute le dossier des headers
include_directories(${PROJECT_SOURCE_DIR}/inc)

# Défini le nom de l'exécutable et son emplacement
add_executable(${PROJECT_NAME} ${SOURCES})
set(EXECUTABLE_OUTPUT_PATH ${PROJECT_SOURCE_DIR})

# Ajoute les fichiers source
target_sources(${PROJECT_NAME}
	PRIVATE
	# ${PROJECT_SOURCE_DIR}/src/*.cpp
)
target_include_directories(${PROJECT_NAME}
	PRIVATE
	${CMAKE_CURRENT_SOURCE_DIR}
)

# Ajoute le flag -lsqlite3
target_link_libraries(${PROJECT_NAME} sqlite3)