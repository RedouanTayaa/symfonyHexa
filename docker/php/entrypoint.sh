#!/bin/bash

# Installation des dépendances avec Composer
composer install

# Exécution des migrations Symfony
php bin/console doctrine:migrations:migrate --no-interaction

# Lancement du serveur PHP-FPM
php-fpm