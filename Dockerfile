FROM php:8.2-cli

# Sistem paketleri
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    librdkafka-dev \
    zlib1g-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer kurulumu
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Kafka PHP extension
RUN pecl install rdkafka && docker-php-ext-enable rdkafka

# 🔥 BURAYA BUNU EKLİYORUZ
# Redis PHP extension
RUN pecl install redis && docker-php-ext-enable redis

# Çalışma dizini
WORKDIR /var/www/html
