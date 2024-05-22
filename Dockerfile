FROM php:8.3-cli

COPY . /app
WORKDIR /app

CMD [ "php", "./server"]