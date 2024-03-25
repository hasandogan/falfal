FROM ubuntu:22.04

ARG DEBIAN_FRONTEND=noninteractive
RUN apt-get update
RUN apt list --upgradable
RUN apt-get update

#Installing the Nginx Web Server
RUN apt-get install nginx -y
RUN apt-get install apt-utils -y
RUN apt-get install git -y
RUN apt-get install curl -y
RUN apt-get install nano -y
RUN apt-get install openssl -y
RUN apt-get install libpq-dev -y
RUN apt update
RUN apt install -y software-properties-common
RUN add-apt-repository -y ppa:ondrej/php
RUN apt update
RUN apt install -y php8.2\
    php8.2-cli\
    php8.2-common\
    php8.2-fpm\
    php8.2-mysql\
    php8.2-zip\
    php8.2-gd\
    php8.2-mbstring\
    php8.2-curl\
    php8.2-xml\
    php8.2-bcmath\
    php8.2-pdo

# Install php-fpm
RUN apt install -y php8.2-fpm php8.2-cli




RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Nginx config
RUN rm /etc/nginx/sites-enabled/default
ADD default.conf /etc/nginx/sites-available/default.conf
RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf
RUN sed -i "s/user  nginx;/user  www-data;/g" /etc/nginx/nginx.conf



# Expose ports.
EXPOSE 80
#EXPOSE 443
WORKDIR /var/www/html/symfony

CMD service php8.2-fpm start && nginx -g "daemon off;"
