FROM php:7.3-fpm-alpine

LABEL maintainer="php73 Docker Maintainers <rol@thooh.com>"

# 默认扩展
#Core,ctype,curl,date,dom,fileinfo,filter,ftp,hash,iconv,json,libxml,mbstring,mysqlnd,openssl,pcre,PDO,pdo_sqlite,Phar,posix,readline,Reflection,session,SimpleXML,sodium,SPL,sqlite3,standard,tokenizer,xml,xmlreader,xmlwriter,zlib

ARG PHP_SWOOLE=4.2.1
ARG PHP_MEMCACHED=3.1.3
ARG PHP_REDIS=4.1.1
ARG PHP_ZENDOPCACHE=7.0.5
ARG PHP_XDEBUG=2.7.2
ARG PHP_MONGODB=1.5.3
ARG PHP_IMAGICK=3.4.3
ARG PHP_AMQP=1.9.4
ARG PHP_GRPC=1.12.0
ARG PHP_PROTOBUF=3.11.2

COPY ./extensions /tmp/extensions

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

#    # install mcrypt  2017-11-30发布的PHP7.2.0已经移除了mcrypt,建议加密的地方用openssl或Sodium (PHP 7.2.0开始用)扩展代替
#    && apk add libmcrypt-dev \
#    && docker-php-ext-install -j$(nproc) mcrypt \

    # php custom extensions
    && cd /tmp/extensions \
    
    # install swoole
    && mkdir swoole \
    && tar -xf swoole-${PHP_SWOOLE}.tgz -C swoole --strip-components=1 \
    && ( cd swoole && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable swoole \

    # install memcached
    && apk add libmemcached-dev \
    && mkdir memcached \
    && tar -xf memcached-${PHP_MEMCACHED}.tgz -C memcached --strip-components=1 \
    && ( cd memcached && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable memcached \

    && mkdir redis \
    && tar -xf redis-${PHP_REDIS}.tgz -C redis --strip-components=1 \
    && ( cd redis && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable redis \

#?    # install zendopcache, 5.6 以前使用opcache
#    && mkdir zendopcache \
#    && tar -xf zendopcache-${PHP_ZENDOPCACHE}.tgz -C zendopcache --strip-components=1 \
#    && ( cd zendopcache && phpize && ./configure && make -j$(nproc) && make install ) \
#    && docker-php-ext-enable opcache \

    # install xdebug
    && mkdir xdebug \
    && tar -xf xdebug-${PHP_XDEBUG}.tgz -C xdebug --strip-components=1 \
    && ( cd xdebug && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable xdebug \

    # install xhprof
    && apk add graphviz \
    && ( cd xhprof/extension && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable xhprof \

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

    # install grpc
    && apk add zlib-dev \
#    && mkdir grpc \
#    && tar -xf grpc-${PHP_GRPC}.tgz -C grpc --strip-components=1 \
    && ( cd grpc && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable grpc \

     # install protobuf
    && mkdir protobuf \
    && tar -xf protobuf-${PHP_PROTOBUF}.tgz -C protobuf --strip-components=1 \
    && ( cd protobuf && phpize && ./configure && make -j$(nproc) && make install ) \
    && docker-php-ext-enable protobuf \

    && rm -rf /tmp/extensions \
    && docker-php-source delete 

WORKDIR /var/www/html