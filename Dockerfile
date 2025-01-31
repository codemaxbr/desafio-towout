FROM php:8.2-fpm-alpine
LABEL maintainer="Lucas Maia <lucas.codemax@gmail.com>"

WORKDIR /var/www

# Instale as dependências necessárias para o Laravel
RUN apk add --no-cache git htop curl bash zip unzip supervisor libpng-dev icu-dev libjpeg-turbo-dev freetype-dev oniguruma-dev openssl-dev libzip-dev mysql-client autoconf g++ make

# Instala as extensões de PHP que o Laravel precisa
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql mbstring zip exif pcntl mysqli opcache bcmath intl

COPY xdebug.ini "${PHP_INI_DIR}/conf.d"

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-3.2.2 \
    && docker-php-ext-enable xdebug

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

RUN rm -rf /var/cache/apk/*

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .
RUN composer install
COPY .env.example .env

#EXPOSE 8000
#CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/public"]

CMD ["php-fpm"]
