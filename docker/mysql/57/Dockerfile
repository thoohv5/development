FROM mysql:5.7

LABEL maintainer="mysql Docker Maintainers <rol@thooh.com>"

RUN mkdir -p /var/log/mysql/ \
&& chown -R mysql:root /var/log/mysql/ /var/lib/mysql/
COPY my.cnf /etc/mysql/conf.d/my.cnf

# docker build -f /Users/thooh/Workspace/php/thoohdock/mysql/version/57/Dockerfile .

COPY my.cnf /etc/mysql/conf.d/my.cnf

ENV LANG C.UTF-8