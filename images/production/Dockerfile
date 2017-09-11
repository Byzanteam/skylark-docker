# NAME:    skylark/base
# VERSION: release

FROM skylark/base:2.3.1-ubuntu
MAINTAINER Phil Chen '06fahchen@gmail.com'

# Skylark specific bits
RUN useradd skylark -s /bin/bash -m -U && \
    mkdir -p /var/www/skylark

WORKDIR /var/www/
COPY skylark.tar.gz /var/www/
RUN tar xfz skylark.tar.gz --strip-components=1 -C skylark && rm skylark.tar.gz

WORKDIR /var/www/skylark
RUN chown -R skylark:skylark /var/www/skylark
USER skylark
RUN bundle config mirror.https://rubygems.org https://gems.ruby-china.org && \
    bundle install --deployment --without test development -j4 && \
    find /var/www/skylark/vendor/bundle -name tmp -type d -exec rm -rf {} +
USER root
