FROM nginx:alpine

LABEL maintainer="nginx Docker Maintainers <rol@thooh.com>"

COPY nginx.conf /etc/nginx/

#RUN apk update \
#    && apk upgrade \
#    && apk --update add logrotate \
#    && apk add --no-cache openssl \
#    && apk add --no-cache bash
#    && adduser -D -H -u 1000 -s /bin/bash www-data
    
WORKDIR /var/www/html