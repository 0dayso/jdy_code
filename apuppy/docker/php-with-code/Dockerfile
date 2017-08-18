FROM docker.sunfund.com/9dy-php:5.6.8-fpm

RUN apt-get update && apt-get install -y \
        python \
        mysql-client \
        && rm -rf /var/lib/apt/lists/*

RUN curl -L http://peak.telecommunity.com/dist/ez_setup.py | python -
RUN easy_install supervisor
RUN echo_supervisord_conf > /etc/supervisord.conf
COPY supervisord.conf /etc/supervisord.conf
RUN mkdir /etc/supervisord.d

COPY php-prod.ini /usr/local/etc/php/php.ini
COPY rsyslogd.conf /etc/rsyslog.d/9douyu.conf
COPY 9douyu.ini /etc/supervisord.d/9douyu.ini
COPY crontab-prod /opt/crontab

COPY core-vendor /opt/src/9douyu-core/vendor
COPY module-vendor /opt/src/9douyu-module/vendor
COPY service-vendor /opt/src/9douyu-service/vendor

COPY 9douyu-service /opt/src/9douyu-service
COPY 9douyu-core /opt/src/9douyu-core
COPY 9douyu-module /opt/src/9douyu-module
