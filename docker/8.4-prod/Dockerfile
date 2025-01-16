FROM ubuntu:22.04

LABEL maintainer="Taylor Otwell"

ARG WWWGROUP
ARG NODE_VERSION=22
ARG MYSQL_CLIENT="mysql-client"
ARG POSTGRES_VERSION=17

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Atualizar e instalar pacotes essenciais
RUN apt-get update && apt-get install -y --no-install-recommends \
    gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 \
    libcap2-bin libpng-dev python2 dnsutils

# Adicionar repositórios e instalar PHP
RUN curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | \
    gpg --dearmor -o /usr/share/keyrings/ppa_ondrej_php.gpg && \
    echo "deb [signed-by=/usr/share/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list && \
    apt-get update && apt-get install -y --no-install-recommends \
    php8.4-cli php8.4-dev php8.4-pgsql php8.4-sqlite3 php8.4-gd php8.4-curl \
    php8.4-imap php8.4-mysql php8.4-mbstring php8.4-xml php8.4-zip \
    php8.4-bcmath php8.4-soap php8.4-intl php8.4-readline php8.4-ldap \
    php8.4-msgpack php8.4-igbinary php8.4-redis php8.4-swoole \
    php8.4-memcached php8.4-pcov php8.4-xdebug php8.4-fpm

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Instalar Node.js e Yarn
RUN curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - && \
    apt-get install -y --no-install-recommends nodejs && \
    npm install -g npm && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor -o /usr/share/keyrings/yarn.gpg && \
    echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list

# Instalar cliente PostgreSQL
RUN curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor -o /usr/share/keyrings/pgdg.gpg && \
    echo "deb [signed-by=/usr/share/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list && \
    apt-get update && apt-get install -y --no-install-recommends postgresql-client-$POSTGRES_VERSION

# Limpeza
RUN apt-get -y autoremove && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Configurar PHP para usar portas baixas
RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.4

# Copiar arquivos do projeto
COPY . /var/www/html
COPY docker/8.4-prod/start-container /usr/local/bin/start-container
COPY docker/8.4-prod/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/8.4-prod/php.ini /etc/php/8.4/cli/conf.d/99-sail.ini
COPY docker/8.4-prod/nginx.conf /etc/nginx/sites-enabled/default
RUN chmod +x /usr/local/bin/start-container

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html

# Instalar dependências PHP e Node.js
RUN composer install --optimize-autoloader --no-dev
RUN npm ci
RUN npm run build

EXPOSE 80

ENTRYPOINT ["start-container"]