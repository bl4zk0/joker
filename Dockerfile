FROM debian:bullseye

# install packages
RUN apt update \
    && apt install -y nginx php7.4 php7.4-fpm vim unzip php7.4-bcmath \
    php7.4-mbstring php7.4-xml php7.4-mysql php7.4-sqlite3 php7.4-curl \
    composer mariadb-server golang-go net-tools supervisor curl

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt install -y nodejs

RUN openssl req -nodes -new -x509 -keyout /etc/ssl/certs/joker.local.key -out \
    /etc/ssl/certs/joker.local.crt -subj "/C=GE/ST=State/L=City/O=Organization/OU=Unit/CN=joker"

RUN go get github.com/mailhog/MailHog

# copy files
COPY dockerconf/bashrc /root/.bashrc
COPY dockerconf/supervisord.conf /etc/supervisor/supervisord.conf
COPY --chown=root:root dockerconf/entrypoint.sh /entrypoint.sh

WORKDIR /var/www/joker

EXPOSE 80
EXPOSE 443
EXPOSE 3306

RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]