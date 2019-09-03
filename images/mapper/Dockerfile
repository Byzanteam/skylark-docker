FROM ruby:2.5.0-stretch

RUN sed -i 's/archive.ubuntu.com/mirrors.163.com/g' /etc/apt/sources.list
RUN apt-get update && apt-get install -y apt-utils
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y curl \
                                                      git-core \
                                                      build-essential \
                                                      libssl-dev libreadline-dev zlib1g-dev \
                                                      libpq-dev \
                                                      nodejs

RUN useradd skylark -s /bin/bash -m -U && mkdir -p /var/www/slp_table_mapper
RUN chown -R skylark:skylark /var/www/slp_table_mapper

RUN gem install bundler

USER skylark
WORKDIR /var/www/slp_table_mapper

COPY ./slp_table_mapper/ /var/www/slp_table_mapper/

USER root

RUN bundle install