# Utiliser une image Node.js comme base
FROM node:14

# Définir le répertoire de travail dans le conteneur
WORKDIR /app

# Copier les fichiers du projet Angular dans le conteneur
COPY . .

# Installer les dépendances de l'application
RUN npm install

# Exposer le port utilisé par l'application Angular (par défaut, le port 4200)
EXPOSE 4200

# Commande de démarrage de l'application
CMD ["npm", "start"]
