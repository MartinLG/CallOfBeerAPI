#!/bin/bash


# Cleaning
sudo rm -rf app/cache/* app/logs/*

# setup mongodb repository
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
echo 'deb http://downloads-distro.mongodb.org/repo/ubuntu-upstart dist 10gen' | sudo tee /etc/apt/sources.list.d/mongodb.list

# install necessary package
sudo apt-get update
sudo apt-get install -y git mongodb-org nginx php5-apcu php5-fpm acl curl php5 php5-mysql php5-curl php5-intl php5-dev php5-mongo php5-imagick libphp-swiftmailer


#install redis
sudo apt-get -y install redis-server
sudo update-rc.d redis-server defaults
sudo service redis-server start

#clean apt
apt-get autoremove -y

# Configure nginx
sudo cp /vagrant/build/vagrant_nginx_host /etc/nginx/sites-available/callofbeerapi
sudo ln -s /etc/nginx/sites-available/callofbeerapi /etc/nginx/sites-enabled/callofbeerapi
sudo service nginx restart

sudo service php5-fpm restart

cd /vagrant

# "Downloading Composer"
curl -sS https://getcomposer.org/installer | php

# "Updating Composer"
php composer.phar update

./update-dev.sh

# "Fix permissions"
#sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX app/cache app/logs
#sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

exit 0
