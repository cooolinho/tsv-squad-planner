#!/bin/bash
SYMFONY_DIRECTORY="$1"
APP_ENV="$2"

echo ""
echo "============================================= cache.sh =========================================================="

echo "> change Directory $SYMFONY_DIRECTORY"
cd  $SYMFONY_DIRECTORY

echo "> cache clear"
php bin/console cache:clear --env=$APP_ENV

echo "> cache warmup"
php bin/console cache:warmup --env=$APP_ENV

echo "============================================= end cache.sh ======================================================"
