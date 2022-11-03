FROM php:8.0-fpm

ADD ./conf.d/extra.ini /usr/local/etc/php/conf.d/

ENV PHP_VERSION="8.0" \
	PSR_VERSION="1.1.0"
# Update

RUN cp /usr/local/bin/php /usr/bin/

RUN apt update -y && \
    apt install -y \
        apt-utils \
        gettext \
        git \
        libgmp-dev \
        libicu-dev \
        libmagickwand-dev \
        libmemcached-dev \
        libpng-dev \
        libpq-dev \
        libyaml-dev \
        libzip-dev \
        locales \
        nano \
        sudo \
        wget \
        zip

# PECL Packages
RUN   pecl install psr-${PSR_VERSION} \
      igbinary \
      msgpack \
      apcu \
      yaml \
      imagick \
      memcached \
      xdebug \
			zephir_parser

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg=/usr/include/ --enable-gd

RUN docker-php-ext-install \
        gd \
        gettext \
        gmp \
        intl \
        pdo_mysql \
        zip

# Install PHP extensions
RUN docker-php-ext-enable \
        psr \
        gettext \
        igbinary \
        msgpack \
        apcu \
        imagick \
        yaml \
        memcached \
        xdebug \
				zephir_parser

# Install Phalcon
WORKDIR /usr/local/src
RUN git clone https://github.com/phalcon/cphalcon.git
WORKDIR /usr/local/src/cphalcon/build
RUN ./install

WORKDIR /usr/local/etc/php/
RUN echo 'extension=phalcon.so' >> ./conf.d/phalcon.ini
RUN docker-php-ext-enable phalcon
