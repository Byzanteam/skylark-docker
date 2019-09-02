FROM ruby:2.5-alpine

RUN useradd skylark -s /bin/bash -m -U && mkdir -p /var/www/slp_table_mapper
RUN chown -R skylark:skylark /var/www/slp_table_mapper

USER skylark
WORKDIR /var/www/slp_table_mapper

COPY ./slp_table_mapper/ /var/www/slp_table_mapper/

USER root
