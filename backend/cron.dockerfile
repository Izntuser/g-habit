FROM php:8.3.1-fpm-alpine

RUN apk update && apk add --no-cache \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY crontab /etc/crontabs/root

CMD ["crond", "-f"]
