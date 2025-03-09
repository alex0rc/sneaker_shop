#!/bin/bash

# Variables
REPO_URL="https://github.com/alex0rc/sneaker_shop"
PROJECT_DIR="C:/xampp/htdocs/sneaker_shop"

# Navegar al directorio del proyecto
cd "$PROJECT_DIR" || { echo "Error: No se pudo acceder al directorio del proyecto."; exit 1; }

# Inicializar el repositorio si no está inicializado
if [ ! -d ".git" ]; then
    git init
    echo "Repositorio Git inicializado en $PROJECT_DIR"
fi

# Agregar el repositorio remoto
git remote add origin "$REPO_URL"

# Agregar archivos y hacer el primer commit
git add .
git commit -m "Initial commit - Proyecto sneaker_shop vinculado a GitHub"

# Subir la rama principal al repositorio remoto
git branch -M main
git push -u origin main

echo "Repositorio conectado a GitHub correctamente."
