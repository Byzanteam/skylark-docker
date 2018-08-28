FROM php:7-fpm

ENV LANG=C.UTF-8

RUN sed -i 's/deb.debian.org/mirrors.aliyun.com/g' /etc/apt/sources.list

RUN apt-get update && apt-get install -y \
      libmemcached-dev \
      zlib1g-dev \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      libmcrypt-dev \
      libpng12-dev \
      libbz2-dev \
      && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mbstring
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo && docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && docker-php-ext-install -j$(nproc) gd mcrypt pdo_mysql bz2

RUN mkdir /var/www/kaoqin
WORKDIR /var/www/kaoqin
COPY ./ /var/www/kaoqin

COPY bin/composer.phar /usr/local/bin/composer
RUN composer config -g repo.packagist composer https://packagist.phpcomposer.com

EXPOSE 9000
