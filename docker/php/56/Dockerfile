FROM php:5.6-fpm-alpine

LABEL maintainer="php56 Docker Maintainers <rol@thooh.com>"

# 默认扩展
#Core,ctype,curl,date,dom,fileinfo,filter,ftp,hash,iconv,json,libxml,mbstring,mysqlnd,openssl,pcre,PDO,pdo_sqlite,Phar,posix,readline,Reflection,session,SimpleXML,sodium,SPL,sqlite3,standard,tokenizer,xml,xmlreader,xmlwriter,zlib

ARG PHP_SWOOLE=4.2.1
ARG PHP_MEMCACHED=3.1.3
ARG PHP_REDIS=4.1.1
ARG PHP_ZENDOPCACHE=7.0.5
ARG PHP_XDEBUG=2.5.5
ARG PHP_MONGODB=1.5.3
ARG PHP_IMAGICK=3.4.3
ARG PHP_AMQP=1.9.4

COPY extensions /tmp/extensions

RUN set -xe; \
    sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories \
    && apk update \
    # 相关依赖必须手动安装
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \

    # php extensions
    && docker-php-source extract \

    # install pdo_mysql
    && docker-php-ext-install -j$(nproc) pdo_mysql \

    # install pcntl
    && docker-php-ext-install -j$(nproc) pcntl \

    # install zip
    && apk add libzip-dev \
    && docker-php-ext-install -j$(nproc) zip \

    # install iconv
    && docker-php-ext-install -j$(nproc) iconv \

    # install gd
    && apk add freetype-dev libjpeg-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \

    # install exif
    && docker-php-ext-install -j$(nproc) exif \

    # install bcmath
    && docker-php-ext-install -j$(nproc) bcmath \

    # install calendar
    && docker-php-ext-install -j$(nproc) calendar \

    # install sockets
    && docker-php-ext-install -j$(nproc) sockets \

    # install bcmath
    && docker-php-ext-install -j$(nproc) bcmath \

    # install soap
    && apk add libxml2-dev \
    && docker-php-ext-install -j$(nproc) soap \

    # php custom extensions
    && cd /tmp/extensions \

    # install redis
    && mkdir redis \
    && tar -xf redis-${PHP_REDIS}.tgz -C redis --strip-components=1 \
    && ( cd redis && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable redis \

    # install xdebug
    && mkdir xdebug \
    && tar -xf xdebug-${PHP_XDEBUG}.tgz -C xdebug --strip-components=1 \
    && ( cd xdebug && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable xdebug \

    # install mongodb
    && mkdir mongodb \
    && tar -xf mongodb-${PHP_MONGODB}.tgz -C mongodb --strip-components=1 \
    && ( cd mongodb && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable mongodb \

    # install imagick
    && apk add imagemagick-dev \
    && mkdir imagick \
    && tar -xf imagick-${PHP_IMAGICK}.tgz -C imagick --strip-components=1 \
    && ( cd imagick && phpize && ./configure --with-imagick=/usr/local && make -j$(nproc) && make install ) \
    && docker-php-ext-enable imagick \

    # install amqp
    && apk add rabbitmq-c-dev \
    && mkdir amqp \
    && tar -xf amqp-${PHP_AMQP}.tgz -C amqp --strip-components=1 \
    && ( cd amqp && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable amqp \

    # install kafka
    && apk add librdkafka librdkafka-dev \
    && ( cd php-rdkafka && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable rdkafka \

    && rm -rf /tmp/extensions \
    && docker-php-source delete 

WORKDIR /var/www/html