FROM mysql:latest

LABEL maintainer="mysql Docker Maintainers <rol@thooh.com>"

RUN mkdir -p /var/log/mysql/ \
&& chown -R mysql:root /var/log/mysql/ /var/lib/mysql/

COPY my.cnf /etc/mysql/conf.d/my.cnf

ENV LANG C.UTF-8