FROM php:8-fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && \
apt-get install -y \
    curl \
    git \
    vim \
#    libpq-dev \
#    libxrender1 \
#    libfontconfig1 \
#    libx11-dev \
#    libjpeg62 \
#    libxtst6 \
#    xvfb \
#    libfontconfig \
#    wkhtmltopdf \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# creates docker user and gives sudo priviledges
#RUN useradd -m docker && echo "docker:docker" | chpasswd && adduser docker sudo

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt update
RUN apt install symfony-cli

RUN pecl install xdebug-3.1.4 \
    && docker-php-ext-enable xdebug

COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/app

#RUN if [[ -z "$uid" &&  -z "$gid"]] ; then usermod -u $uid www-data ; fi
#
#RUN chmod 770 -R /usr/src/app
#RUN chown www-data:www-data -R /usr/src/app

# vendor executables accessible globaly in container CMD
RUN PATH=$PATH:/var/www/app/vendor/bin:bin
CMD ["php-fpm"]
ENTRYPOINT ["/entrypoint.sh"]