######################
### PHP BASE IMAGE ###
######################
FROM php:8.1-fpm-alpine AS backend-base

ARG DOCKER_UID
ARG DOCKER_GID

# Basics
RUN apk --update --no-cache add bash nano

# Add easy extension installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install required extensions
RUN chmod +x /usr/local/bin/install-php-extensions

# Install default required extensions
RUN install-php-extensions apcu calendar igbinary pcntl zip

# Install http extension
RUN install-php-extensions http

# Install MySQL
RUN install-php-extensions pdo_mysql mysqli

# Install GD extension
RUN install-php-extensions gd

# Change UID
RUN apk --no-cache add shadow && \
    usermod -u ${DOCKER_UID} www-data && \
    groupmod -g ${DOCKER_GID} www-data

# Install opcache
RUN install-php-extensions opcache
ADD docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install Composer from image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install npm and yarn
RUN apk --update --no-cache add npm yarn

USER www-data

############################
### BACKEND LOCAL IMAGE ####
############################
FROM backend-base AS backend-local

USER root

# Setup entrypoint with startup commands
COPY docker/php/docker-entrypoint /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

USER www-data

#################################
### BUILD PRODUCTION IMAGE ####
#################################
FROM backend-base as build-production

COPY --chown=www-data:www-data . .
RUN composer install --no-dev --no-scripts

ENV NODE_ENV=production

RUN yarn install
RUN yarn encore production

#################################
### BACKEND PRODUCTION IMAGE ####
#################################
FROM backend-base as backend-production

USER root

COPY --chown=www-data:www-data . .
COPY --from=build-production --chown=www-data:www-data /var/www/html/public/build /var/www/html/public/build

USER www-data

RUN composer install --no-dev

##################################
### WEBSERVER NGINX BASE IMAGE ###
##################################
FROM nginx:stable AS webserver-base

###################################
### WEBSERVER NGINX LOCAL IMAGE ###
###################################
FROM webserver-base AS webserver-local

ADD docker/nginx/local/conf.d-default.conf /etc/nginx/conf.d/default.conf
ADD docker/nginx/local/nginx.conf /etc/nginx/nginx.conf

########################################
### WEBSERVER NGINX PRODUCTION IMAGE ###
########################################
FROM webserver-base AS webserver-production

RUN apt update && apt install -y logrotate cron

COPY docker/nginx/production/logrotate-nginx.conf /etc/logrotate.d/nginx
COPY docker/nginx/production/docker-entrypoint.sh /docker-entrypoint.sh

RUN chmod 644 /etc/logrotate.d/nginx
RUN chmod +x /docker-entrypoint.sh

RUN mkdir /static-logs
RUN touch /static-logs/access.log
RUN touch /static-logs/error.log

ADD docker/nginx/production/conf.d-default.conf /etc/nginx/conf.d/default.conf
ADD docker/nginx/production/nginx.conf /etc/nginx/nginx.conf

COPY --from=backend-production /var/www/html/public /var/www/html/public
