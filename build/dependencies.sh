#!/bin/bash
SYMFONY_DIRECTORY="$1"
APP_ENV="$2"

echo ""
echo "=========================================== dependencies.sh ====================================================="
cd  $SYMFONY_DIRECTORY

echo "> install composer.json dependencies"
composer install

echo "> install package.json dependencies"
npm install

echo "> dump .env variables"
composer dump-env $APP_ENV

echo "========================================== end dependencies.sh =================================================="
