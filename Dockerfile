FROM grpc/php

MAINTAINER Dmitrii Vinogradov "df.vinogradoff@gmail.com"

RUN apt-get update && apt-get install zsh -y
RUN pecl install protobuf-3.4.0
RUN pecl install xdebug
RUN docker-php-ext-enable protobuf
RUN docker-php-ext-enable xdebug

WORKDIR /app
