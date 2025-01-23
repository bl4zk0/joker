#!/bin/bash
chmod 700 /entrypoint.sh

#configure nginx vhost
chown -R www-data:www-data /var/www/joker
cp /var/www/joker/dockerconf/nginx.conf /etc/nginx/sites-available/joker.local
rm /var/www/joker/Dockerfile && rm -r /var/www/joker/dockerconf
rm /etc/nginx/sites-enabled/default
ln -s /etc/nginx/sites-available/joker.local /etc/nginx/sites-enabled/joker.local

# configure nginx & mysql sockets
mkdir -p /run/mysqld && mkdir -p /run/php
chown mysql:mysql /run/mysqld && chown www-data:www-data /run/php

sed -i 's/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/' /etc/mysql/mariadb.conf.d/50-server.cnf
sed -i 's/display_errors = Off/display_errors = On/' /etc/php/7.4/fpm/php.ini
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/7.4/fpm/php.ini
key="$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 16)"
sed -i "s/PUSHER_APP_SECRET=jokersecret101010/PUSHER_APP_SECRET=$key/" /var/www/joker/.env

php-fpm7.4
mysqld &
sleep 5
nginx

mysql -u root -e 'CREATE DATABASE joker;'
mysql -u root -e 'ALTER USER root@localhost IDENTIFIED BY "S3cret_p4s$word123$";'
mysql -u root -e 'FLUSH PRIVILEGES;'
php /var/www/joker/artisan key:generate
php /var/www/joker/artisan migrate:fresh
php /var/www/joker/artisan db:seed

/usr/bin/supervisord -c /etc/supervisor/supervisord.conf