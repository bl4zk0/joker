#!/bin/bash
chmod 600 /entrypoint.sh

#configure nginx vhost
chown -R www-data:www-data /var/www/joker
cp /var/www/joker/dockerconf/nginx.conf /etc/nginx/sites-available/joker.local
rm /var/www/joker/Dockerfile && rm -r /var/www/joker/dockerconf
rm /etc/nginx/sites-enabled/default
ln -s /etc/nginx/sites-available/joker.local /etc/nginx/sites-enabled/joker.local

# configure nginx & mysql sockets
mkdir -p /run/mysqld && mkdir -p /run/php
chown mysql:mysql /run/mysqld && chown www-data:www-data /run/php

sed -i 's/display_errors = Off/display_errors = On/' /etc/php/7.4/fpm/php.ini
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/7.4/fpm/php.ini

php-fpm7.4
mysqld &
sleep 5
nginx

mysql -u root -e 'CREATE DATABASE joker;'
mysql -u root -e 'ALTER USER root@localhost IDENTIFIED VIA mysql_native_password;'
mysql -u root -e 'FLUSH PRIVILEGES;'
php /var/www/joker/artisan migrate

/usr/bin/supervisord -c /etc/supervisor/supervisord.conf