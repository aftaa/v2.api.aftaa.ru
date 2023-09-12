FROM php:8.2-fpm

RUN apt-get update && apt-get install -y curl git mc wget \
    libxml2-dev \
    libxslt-dev \
    libzip-dev

RUN docker-php-ext-install sockets intl xsl zip pdo pdo_mysql mysqli

WORKDIR /var/www/v2.api.aftaa.ru

RUN git config --global user.name "Maxim Gabidullin"
RUN git config --global user.email "after@ya.ru"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' |  bash -E
RUN apt-get install -y symfony-cli

CMD ["php-fpm"]
