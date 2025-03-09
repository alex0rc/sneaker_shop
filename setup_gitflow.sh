#!/bin/bash

# Variables
PROJECT_DIR="C:/xampp/htdocs/sneaker_shop"

# Navegar al directorio del proyecto
cd "$PROJECT_DIR" || { echo "Error: No se pudo acceder al directorio del proyecto."; exit 1; }

# Configurar Git Flow si no está instalado
if ! git flow init -d; then
    echo "Git Flow no está instalado o hubo un problema al configurarlo."
    exit 1
fi

# Crear la estructura de ramas según Git Flow
git checkout -b develop
git push -u origin develop

# Crear ramas de características (features)
git checkout -b feature1 develop
git push -u origin feature1

git checkout -b feature2 develop
git push -u origin feature2

# Regresar a la rama develop
git checkout develop

echo "Workflow Git Flow configurado correctamente con las ramas:"
echo "- main (para producción)"
echo "- develop (para desarrollo)"
echo "- feature1 (para nueva funcionalidad 1)"
echo "- feature2 (para nueva funcionalidad 2)"
