FROM php:7.3-fpm-alpine
RUN apk add --no-cache libzip-dev zip
RUN docker-php-ext-install pdo_mysql zip
RUN apk add --no-cache $PHPIZE_DEPS \
	&& pecl install xdebug \
	&& docker-php-ext-enable xdebug
RUN apk add composer
RUN mkdir -p /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/storage
EXPOSE 9000
CMD ["php-fpm","-F","-R"]
