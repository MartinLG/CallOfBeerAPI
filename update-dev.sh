#!/bin/bash

# enable this if you added new bundle in composer.json
#php composer.phar update

if [ $# -eq '0' ]; then
    ENVS='dev'
else
    ENVS=$*
fi

for ENV in $ENVS
do
    echo "Updating : " $ENV
    app/console assetic:dump --env=$ENV
    app/console assets:install --env=$ENV --symlink
    app/console cache:clear --env=$ENV
done