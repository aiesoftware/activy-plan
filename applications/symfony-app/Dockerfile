FROM php:8.0-fpm

ARG XDEBUG
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    sqlite3

RUN if [ ${XDEBUG} ] ; then \
	pecl install xdebug && \
	docker-php-ext-enable xdebug && \
	docker-php-ext-install pdo_mysql soap intl \
	  && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	  && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	  && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	  && echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	  && echo "xdebug.client_port=9002" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	  && echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ;\
fi;

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./ .
COPY ./docker/.env .
RUN composer install


# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
