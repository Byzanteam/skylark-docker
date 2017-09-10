# NAME:    skylark/base
# VERSION: release
FROM ubuntu:16.04

MAINTAINER Phil Chen '06fahchen@gmail.com'

RUN echo 2.0.`date +%Y%m%d` > /VERSION

ENV RUBY_VERSION 2.3.1

RUN sed -i 's/archive.ubuntu.com/mirrors.163.com/g' /etc/apt/sources.list
RUN apt-get update && apt-get -y upgrade

RUN apt-get -y install curl \
                       git-core \
                       build-essential \
                       redis-tools \
                       libpq-dev \
                       libssl-dev libreadline-dev zlib1g-dev \
                       nodejs \
                       graphicsmagick \
                       nginx \
                       tzdata

COPY resources/华文黑体.ttf /usr/share/fonts/truetype/
COPY resources/type.mgk /tmp/
COPY resources/type-windows.mgk /tmp/
RUN find /usr/lib -name type-windows.mgk -type f -exec cp /tmp/type-windows.mgk {} +
RUN find /usr/lib -name type.mgk -type f -exec cp /tmp/type.mgk {} +

ENV RUBY_BUILD_MIRROR_URL=https://cache.ruby-china.org
RUN echo 'gem: --no-document' >> /usr/local/etc/gemrc && \
    mkdir /src && cd /src && \
    git clone https://github.com/rbenv/ruby-build.git && \
    cd /src/ruby-build && ./install.sh && \
    cd / && rm -rf /src/ruby-build && \
    ruby-build $RUBY_VERSION /usr/local/

RUN gem update --system && gem install bundler && gem cleanup


# clean up for docker squash
RUN   rm -fr /usr/share/man && \
      rm -fr /usr/share/doc && \
      rm -fr /usr/share/vim/vim74/tutor && \
      rm -fr /usr/share/vim/vim74/doc && \
      rm -fr /usr/share/vim/vim74/lang && \
      rm -fr /usr/local/share/doc && \
      rm -fr /usr/local/share/ruby-build && \
      rm -fr /root/.gem && \
      rm -fr /root/.npm && \
      rm -fr /tmp/* && \
      rm -fr /usr/share/vim/vim74/spell/en*

# Clean apt-get
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/*
