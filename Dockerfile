FROM php:7.4-cli as base
LABEL maintainer="Martin Brooksbank <martin@bosslabs.co.uk>"

# Install necessary OS packages
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y \
        git \
        libzip-dev \
        zlib1g-dev \
        libxml2-dev

# Install necessary PHP packages
RUN docker-php-ext-install \
        zip \
        xml

# Copy the application files
COPY . /app

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install Composer packages
RUN composer install -q -n

# Run the get-results command and accept args from stdin
ENTRYPOINT ["php", "/app/run.php", "app:convert"]