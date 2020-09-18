FROM php:7.4-apache

LABEL maintainer="Thomas Wiringa <thomas.wiringa@gmail.com>"

# Install required packages
RUN apt-get update -y \
 && apt-get install -y --no-install-recommends \
    git wget unzip \
    libonig-dev libmagickwand-dev \
    libxrender-dev  libfontconfig-dev libxext-dev

# Install php modules
RUN docker-php-ext-install \
    mbstring \
    pdo_mysql \
    tokenizer \
    opcache

RUN pecl install imagick \
 && docker-php-ext-enable imagick

# Copy PHP config files
ADD ./php.ini /usr/local/etc/php/conf.d

# Set the www-data user id to 1000
RUN usermod -u 1000 www-data

# Symlink artisan
RUN ln -s /var/www/html/artisan /usr/local/bin/artisan

# Install composer
RUN wget https://getcomposer.org/composer.phar
RUN chmod +x composer.phar
RUN mv composer.phar /usr/local/bin/composer

# Cleanup
RUN apt-get clean -y \
 && rm -r /var/lib/apt/lists/*

WORKDIR /var/www/html

# Add and extract the tar to the webroot
ADD ./deployment.tar.gz /var/www/html

RUN php /var/www/html/artisan storage:link

# Copy the apache vhost config
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite
RUN a2enmod rewrite
