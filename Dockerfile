FROM php:8.3-cli

RUN docker-php-ext-install sockets && docker-php-ext-enable sockets

COPY . /app
WORKDIR /app

EXPOSE 80

CMD ["php", "./server"]