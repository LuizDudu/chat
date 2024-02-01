FROM php:8.3.2-cli-alpine3.19

ARG ENVIRONMENT=development

RUN apk update \
    && apk add git bash

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');"

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS}

RUN git clone https://github.com/swoole/swoole-src.git && \
    cd swoole-src && \
    phpize && \
    ./configure && \
    make && make install

RUN docker-php-ext-enable swoole

RUN apk add nodejs npm

WORKDIR /app

COPY . .

RUN if [ "$ENVIRONMENT" = "production" ]; then \
    php ../composer.phar i --no-dev --optimize-autoloader; \
  else \
    hp ../composer.phar i --optimize-autoloader; \
    npm install; \
  fi

RUN php ../composer.phar du

EXPOSE 8080

CMD ["php", "public/index.php"]
