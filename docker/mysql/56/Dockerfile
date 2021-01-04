FROM mysql:5.6

LABEL maintainer="mysql Docker Maintainers <rol@thooh.com>"

RUN mkdir -p /var/log/mysql/ \
&& chown -R mysql:root /var/log/mysql/ /var/lib/mysql/
COPY my.cnf /etc/mysql/conf.d/my.cnf

# docker build -f /Users/thooh/Workspace/php/thoohdock/mysql/version/56/Dockerfile .

ENV LANG C.UTF-8