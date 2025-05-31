#!/bin/bash
#Definition des variables
GIT_REPO="https://github.com/delrodie/gvtc.git"
DEPLOY_DIR="/www/gvtc/v1"
BRANCH="master"

echo "Connexion au serveur et mise a jour du projet..."

#Se rendre dans le dossier du projte
cd DEPLOY_DIR || { echo "Erreur : Impossible d'acceder au repertoire $DEPLOY_DIR"; exit 1;}

# Installation des dependances Symfony
echo "Installation des dépendances ..."
composer install --optimize-autoloader || { echo "Erreur lors de l'installation des dépendances."; exit 1;}

# Mise à jour de la base de données
echo "Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction || {echo "Erreur lors des migrations."; exit 1;}

# Nettoyage du cache symfony
echo "Nettoyage du cache..."
php bin/console cache:clear || {echo "Erreur lors du nettoyage du cache."; exit 1;}

# Changement des permissions si necessaires
echo "Vérification des permissions..."
chmod -R 755 $DEPLOY_DIR
chown -R www-data:www-data $DEPLOY_DIR

# Redémarrage du serveur web
#echo "Redémarrage du serveur..."
#service apache2 restart || echo "Impossible de redémarrer le serveur, vérifie manuellement."

echo "Déploiement terminé avec succès!"
