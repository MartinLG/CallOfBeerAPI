#!/bin/bash

# enable this if you added new bundle in composer.json
#php composer.phar update

if [ $# -eq '0' ]; then
    ENVS='app'
else
    ENVS=$*
fi

for ENV in $ENVS
do
    echo "Updating : " $ENV
    app/console assets:install --env=$ENV
    app/console assetic:dump --env=$ENV --no-debug
    app/console cache:clear --env=$ENV --no-debug
done