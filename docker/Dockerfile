FROM php:8.3-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    zip unzip git && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

RUN composer require --dev phpunit/phpunit

# Depends on your environment settings 
# (adjust UID and GID as necessary based on your host system).
#RUN groupadd -g 1000 appgroup && \
#    useradd -m -u 1000 -g appgroup appuser

#USER appuser

CMD ["bash"]
