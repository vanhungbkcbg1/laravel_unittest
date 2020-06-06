FROM php:7.3-fpm-alpine
RUN docker-php-ext-install pdo_mysql
RUN apk add --no-cache $PHPIZE_DEPS \
	&& pecl install xdebug \
	&& docker-php-ext-enable xdebug
EXPOSE 9000
CMD ["php-fpm","-F","-R"]
