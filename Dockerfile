FROM php:8.0-apache
EXPOSE 80
EXPOSE 443

# set up PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy code
COPY --chown=www-data:www-data --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --chown=www-data:www-data . /var/www/tmp
COPY --chown=www-data:www-data public/.htaccess /var/www/html/.htaccess
RUN ls -la /var/www/tmp/
RUN mv /var/www/tmp/* /var/www/html
RUN mv /var/www/tmp/.env /var/www/html/.env
COPY --chown=www-data:www-data public/.htaccess /var/www/html/.htaccess
RUN rm -rf /var/www/tmp
RUN cat php.ini >> "$PHP_INI_DIR/php.ini"
COPY --chown=www-data:www-data starter.sh /var/www/html
RUN chmod +x /var/www/html/starter.sh

# Apache configuration
COPY --chown=www-data:www-data apache2/backend.conf /etc/apache2/sites-available/backend.conf

# Install packages
RUN apt update
RUN apt install cron git zip curl sudo unzip libicu-dev libbz2-dev libpng-dev libjpeg-dev libzip-dev libmcrypt-dev libreadline-dev libfreetype6-dev g++ libsodium-dev ffmpeg -y

RUN rm /etc/apache2/sites-enabled/000-default.conf
RUN a2ensite backend
RUN a2enmod rewrite headers

# Common PHP Extensions
RUN docker-php-ext-install \
    zip \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    pdo_mysql \
    sodium

# Configure GD to use jpeg and freetype
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd

# Ensure PHP logs are captured by the container
ENV LOG_CHANNEL=stderr

WORKDIR /var/www/html
RUN docker-php-ext-install exif
RUN composer install --no-dev 
#RUN php artisan storage:link && yes | php artisan migrate && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan optimize:clear
# RUN echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1" | crontab -u www-data - 
RUN cd .. && chown -R www-data:www-data html/

# The default apache run command
CMD ["sh", "-c", "/var/www/html/starter.sh"]
