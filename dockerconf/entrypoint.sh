#!/bin/bash
chmod 700 /entrypoint.sh

# install dependencies
cp .env.example .env && composer install && \
php artisan storage:link && npm install && npm run $(grep APP_ENV .env | cut -d '=' -f2)

# permission to write logs
chown -R www-data:www-data /var/www/joker/storage

# configure nginx vhost
cp /var/www/joker/dockerconf/nginx.conf /etc/nginx/sites-available/joker.local
rm /etc/nginx/sites-enabled/default
ln -s /etc/nginx/sites-available/joker.local /etc/nginx/sites-enabled/joker.local

# configure nginx & mysql sockets
mkdir -p /run/mysqld && mkdir -p /run/php
chown mysql:mysql /run/mysqld && chown www-data:www-data /run/php

sed -i 's/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/' /etc/mysql/mariadb.conf.d/50-server.cnf
sed -i 's/display_errors = Off/display_errors = On/' /etc/php/7.4/fpm/php.ini
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/7.4/fpm/php.ini
PUSHER_KEY="$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 16)"
DB_PASS="$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 16)"
sed -i "s/PUSHER_APP_SECRET=/PUSHER_APP_SECRET=$PUSHER_KEY/" /var/www/joker/.env
sed -i "s/DB_PASSWORD=/DB_PASSWORD=$DB_PASS/" /var/www/joker/.env

php-fpm7.4
mysqld &
while ! mysqladmin ping -h'localhost' --silent; do echo 'mysql not up' && sleep .2; done
nginx

mysql -u root -e 'CREATE DATABASE joker;'
mysql -u root -e "ALTER USER root@localhost IDENTIFIED BY '$DB_PASS';"
mysql -u root -p"$DB_PASS" -e 'FLUSH PRIVILEGES;'
php /var/www/joker/artisan key:generate --force
php /var/www/joker/artisan migrate:fresh --force
php /var/www/joker/artisan db:seed --force

/usr/bin/supervisord -c /etc/supervisor/supervisord.conf