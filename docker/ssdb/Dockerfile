FROM debian:stretch-slim

LABEL maintainer="SSDB Docker Maintainers <rol@thooh.com>"

ENV SSDB_VERSION master

RUN set -ex \
    && apt-get update \
    && apt-get -y install wget zip make autoconf gcc g++ python\
    && mkdir -p /usr/src/ssdb \
    && cd /usr/src/ssdb \
    && wget -O ssdb.zip --no-check-certificate https://github.com/ideawu/ssdb/archive/${SSDB_VERSION}.zip \
    && unzip ssdb.zip \
    && rm ssdb.zip \
    # 系统时钟有问题
    && cd ssdb-master/deps/snappy-1.1.0 \
    && ./configure \
    && make -j "$(nproc)"\
    && cd ../.. \
    && make -j "$(nproc)" \
    # optional, install ssdb in /usr/local/ssdb
    && make install \
    && rm -r /usr/src/ssdb

RUN mkdir -p /var/log/ssdb
RUN touch /var/log/ssdb/ssdb.access.log

RUN mkdir -p /usr/local/ssdb
COPY ssdb.conf /usr/local/ssdb/ssdb.conf

EXPOSE 8888

CMD ["/usr/local/ssdb/ssdb-server", "/usr/local/ssdb/ssdb.conf"]