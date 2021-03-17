#!/bin/bash
SYMFONY_DIRECTORY="$1"
APP_ENV="$2"

echo ""
echo "============================================== assets.sh ========================================================"
cd  $SYMFONY_DIRECTORY

echo "> install assets"
php bin/console assets:install --env=$APP_ENV

echo "> webpack build"
npm run $APP_ENV

echo "============================================= end assets.sh ====================================================="
