FROM kaotu/image-laravel:latest

RUN apt-get update -y
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /app
ENV APPLICATION_ROOT /app

COPY artisan $APPLICATION_ROOT
COPY package.json $APPLICATION_ROOT
COPY phpunit.xml $APPLICATION_ROOT
COPY server.php $APPLICATION_ROOT
COPY webpack.mix.js $APPLICATION_ROOT

COPY ./bootstrap $APPLICATION_ROOT/bootstrap
COPY ./config $APPLICATION_ROOT/config
COPY ./public $APPLICATION_ROOT/public
COPY ./resources $APPLICATION_ROOT/resources
COPY ./storage $APPLICATION_ROOT/storage

COPY ./docker/api/php-config.ini /usr/local/etc/php/conf.d/php-config.ini
COPY ./docker/api/www.conf /usr/local/etc/php-fpm.d/www.conf

COPY ./app $APPLICATION_ROOT/app
COPY ./database $APPLICATION_ROOT/database
COPY ./routes $APPLICATION_ROOT/routes
COPY ./tests $APPLICATION_ROOT/tests
COPY composer.json $APPLICATION_ROOT

RUN composer update --no-scripts
RUN composer install --no-scripts

RUN php artisan config:clear

RUN chown -R www-data:www-data $APPLICATION_ROOT
RUN chmod 755 -R $APPLICATION_ROOT/storage/
RUN chmod 775 -R $APPLICATION_ROOT/storage/framework
RUN chmod 775 -R $APPLICATION_ROOT/public

VOLUME $APPLICATION_ROOT

EXPOSE 9000
