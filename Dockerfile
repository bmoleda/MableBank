FROM composer:2 AS composer
# Get PHP 8.4.x:
FROM php:8.4-cli
# PHP extensions + runtime tools:
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libicu-dev \
        libzip-dev \
        libonig-dev \
    && docker-php-ext-install -j"$(nproc)" \
        intl \
        zip \
        bcmath \
        pcntl \
        mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
# Composer:
COPY --from=composer /usr/bin/composer /usr/bin/composer
# Startup orchestration:
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
WORKDIR /MableBank
ENTRYPOINT ["entrypoint.sh"]
CMD ["tail", "-f", "/dev/null"]
