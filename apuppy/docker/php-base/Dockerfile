FROM docker.sunfund.com/php:5.6.8-fpm
RUN ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
ADD sources.list /etc/apt/sources.list
RUN apt-get update && apt-get install -y \
        vim \
        libmemcached-dev \
        libmcrypt-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng12-dev \
        cron \
        rsyslog \
        && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-install mysqli \
        && docker-php-ext-install pdo_mysql \
        && docker-php-ext-install zip \
        && docker-php-ext-install gd \
        && docker-php-ext-install mbstring \
        && docker-php-ext-install mcrypt \
        && docker-php-ext-install sockets

RUN pecl install binpack && pecl install redis-2.2.8 && pecl install memcached-2.2.0
COPY .bashrc /root/
RUN curl -L https://phar.phpunit.de/phpunit-5.7.phar >> /tmp/phpunit.phar && mv /tmp/phpunit.phar /usr/local/bin/phpunit && chmod a+x /usr/local/bin/phpunit

COPY conf/php-dev.ini /usr/local/etc/php/php.ini
COPY conf/php-fpm.conf /usr/local/etc/php-fpm.conf

COPY run.sh /usr/local/bin/run.sh
