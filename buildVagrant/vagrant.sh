#!/bin/bash


# Cleaning
sudo rm -rf app/cache/* app/logs/*

# setup mongodb repository
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
echo 'deb http://downloads-distro.mongodb.org/repo/ubuntu-upstart dist 10gen' | sudo tee /etc/apt/sources.list.d/mongodb.list

# setup elasticsearch repository
wget -qO - https://packages.elasticsearch.org/GPG-KEY-elasticsearch | sudo apt-key add -
sudo add-apt-repository "deb http://packages.elasticsearch.org/elasticsearch/1.4/debian stable main"

# setup mysql database
debconf-set-selections <<< 'mysql-server mysql-server/root_password password MySuperPassword'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password MySuperPassword'

# install necessary package
sudo apt-get update
sudo apt-get install -y git mongodb-org nginx php5-apcu php5-fpm acl curl php5 php5-mysql php5-curl php5-intl php5-dev php5-mongo php5-imagick libphp-swiftmailer openjdk-7-jdk elasticsearch mysql-server

# run elasticsearch as a service
sudo update-rc.d elasticsearch defaults 95 10
sudo /etc/init.d/elasticsearch start

#install redis
sudo apt-get -y install redis-server
sudo update-rc.d redis-server defaults
sudo service redis-server start

#clean apt
apt-get autoremove -y

# Configure nginx
sudo cp /vagrant/buildVagrant/vagrant_nginx_host /etc/nginx/sites-available/callofbeerapi
sudo ln -s /etc/nginx/sites-available/callofbeerapi /etc/nginx/sites-enabled/callofbeerapi
sudo service nginx restart

sudo service php5-fpm restart

cd /vagrant

# "Downloading Composer"
curl -sS https://getcomposer.org/installer | php

# "Updating Composer"
php composer.phar update

# sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX app/cache app/logs
# sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

sudo chmod -R 777 app/cache
sudo chmod -R 777 app/logs

php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console fos:elastica:populate

./update-dev.sh

exit 0
