FROM registry.cn-hangzhou.aliyuncs.com/skylark/base:2.5.0-jemalloc-ubuntu
MAINTAINER Phil Chen '06fahchen@gmail.com'

RUN useradd skylark -s /bin/bash -m -U && mkdir -p /var/www/slp_table_mapper
RUN chown -R skylark:skylark /var/www/slp_table_mapper

USER skylark
WORKDIR /var/www/slp_table_mapper

COPY ./slp_table_mapper/ /var/www/slp_table_mapper/

USER root
