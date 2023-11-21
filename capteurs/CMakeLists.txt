cmake_minimum_required(VERSION 3.7)

# Défini le nom du projet
project(recupMesures LANGUAGES CXX)

# Ajoute le dossier contenant les fichiers .cpp à compiler
add_subdirectory(src)

# Add ssh2 library
#find_package(libssh2 REQUIRED CONFIG)
#include_directories("inc/")

#add_executable(recupMesures "src/agregateur/main.cpp")
#target_link_libraries(${PROJECT_NAME} PRIVATE ${LIBSSH2_LIBRARIES})