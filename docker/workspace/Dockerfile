FROM php_php56:latest

COPY ./.ssh /root/.ssh

#####################################
# Composer:
#####################################

# Install composer and add its bin to the PATH.
RUN php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer \
    && composer config -g repo.packagist composer https://packagist.phpcomposer.com



# Install phpuint and add its bin to the PATH.
#RUN wget -q -O phpunit.phar https://phar.phpunit.de/phpunit-7.phar\
COPY extensions /tmp/extensions
RUN chmod +x /tmp/extensions/phpunit.phar \
    && mv /tmp/extensions/phpunit.phar /usr/local/bin/phpunit

#RUN wget -q -O phpunit.phar https://phar.phpunit.de/phpunit-4.8.9.phar \
#    && chmod +x phpunit.phar \
#    && mv phpunit.phar /usr/local/bin/phpunit


RUN apk add git

RUN apk add openssh