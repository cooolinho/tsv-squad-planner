#!/bin/bash
SYMFONY_DIRECTORY="$1"
APP_ENV="$2"

echo ""
echo "=========================================== migrations.sh ======================================================="
cd  $SYMFONY_DIRECTORY

echo "> update database"
php bin/console doctrine:migrations:migrate --no-interaction --env=$APP_ENV

echo "=========================================== end migrations.sh ==================================================="
