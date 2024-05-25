FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    sudo

RUN groupadd dev -g 1000
RUN useradd dev -g dev -d /home/dev -m
RUN echo '%dev ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
USER dev:dev

RUN sudo a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

