#FROM docker.sunfund.com/nginx:1.11-alpine
FROM docker.sunfund.com/9dy-nginx:1.11-alpine

#COPY Shanghai /usr/share/zoneinfo/Asia/Shanghai
#RUN ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime

COPY nginx.conf /etc/nginx/nginx.conf
COPY docker-sites /etc/nginx/docker-sites
COPY archive.sh /opt/archive.sh
COPY run.sh /usr/local/bin/run.sh
COPY crontab-php-nginx /opt/crontab

COPY 9douyu-service/public /opt/src/9douyu-service/public
COPY 9douyu-core/public /opt/src/9douyu-core/public
COPY 9douyu-module/public /opt/src/9douyu-module/public

