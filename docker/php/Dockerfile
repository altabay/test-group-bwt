FROM php:8.3-zts-bookworm

ARG PHP_CUSTOM_ABS_PATH="/usr/local/etc/php/conf.d/custom-php.ini"

RUN apt-get update \
    && ACCEPT_EULA=Y apt-get install -y \
        git \
        libicu-dev \
        unzip \
        libonig-dev \
    && docker-php-ext-install bcmath mbstring \
    && echo "date.timezone = UTC" >> ${PHP_CUSTOM_ABS_PATH} \
    && echo "short_open_tag = Off" >> ${PHP_CUSTOM_ABS_PATH} \
    && echo "memory_limit = -1" >> ${PHP_CUSTOM_ABS_PATH}

# composer + symfony cmd-tools
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl https://getcomposer.org/download/2.8.1/composer.phar -L -o /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && curl https://get.symfony.com/cli/installer -L -o /tmp/symfony-install \
    && chmod +x /tmp/symfony-install \
    && /tmp/symfony-install --install-dir=/usr/local/bin \
    && rm /tmp/symfony-install

CMD ["bash", "-c", "composer install --prefer-dist --no-progress --no-interaction"]
