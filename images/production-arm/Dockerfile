# NAME:    skylark/base
# VERSION: release

FROM registry.cn-hangzhou.aliyuncs.com/skylark/base-arm:2.5.0-jemalloc-ubuntu
MAINTAINER Phil Chen '06fahchen@gmail.com'

# Skylark specific bits
RUN useradd skylark -s /bin/bash -m -U && mkdir -p /var/www/skylark
RUN chown -R skylark:skylark /var/www/skylark

USER skylark
WORKDIR /var/www/skylark

COPY ./skylark/ /var/www/skylark/

USER root
