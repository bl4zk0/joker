FROM debian:bullseye

# install packages
RUN apt update \
    && apt install -y nginx php7.4 php7.4-fpm vim unzip php7.4-bcmath \
    php7.4-mbstring php7.4-xml php7.4-mysql php7.4-sqlite3 php7.4-curl \
    composer nodejs npm mariadb-server golang-go net-tools supervisor

RUN go get github.com/mailhog/MailHog

# copy files
COPY . /var/www/joker
COPY dockerconf/bashrc /root/.bashrc
COPY dockerconf/supervisord.conf /etc/supervisor/supervisord.conf
COPY --chown=root:root dockerconf/entrypoint.sh /entrypoint.sh

WORKDIR /var/www/joker
RUN cp .env.example .env && composer install &&\
    php /var/www/joker/artisan storage:link && npm install

EXPOSE 80
EXPOSE 443
EXPOSE 6001
EXPOSE 8025
EXPOSE 3306

RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]