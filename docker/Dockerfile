FROM php:5.6.32-apache-jessie
MAINTAINER Andrei Pirjoleanu <andrei.pirjoleanu@avangate.com>

RUN pecl channel-update pecl.php.net
RUN pecl install xdebug

ADD ./xdebug.ini /usr/local/etc/php/conf.d/
RUN cat /usr/local/etc/php/conf.d/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN rm /usr/local/etc/php/conf.d/xdebug.ini

ADD ./multi-routing.conf /etc/apache2/sites-available/
RUN a2ensite multi-routing
RUN a2enmod rewrite

WORKDIR /var/www

RUN apt-get update
RUN apt-get upgrade -y

RUN apt-get install sudo -y
RUN apt-get install git -y
RUN apt-get install zip unzip -y
RUN apt-get install vim -y

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN composer global require 'phpunit/phpunit'
ENV PATH="~/.composer/vendor/bin:${PATH}"

