# Gunakan base image PHP 8.1 FPM versi Alpine untuk ukuran yang lebih kecil
FROM php:8.1-fpm-alpine

# Set working directory di dalam container
WORKDIR /var/www/html

# Instalasi dependensi sistem yang dibutuhkan
# build-base: untuk kompilasi
# oniguruma-dev, libxml2-dev, libzip-dev, etc: untuk ekstensi PHP
# git, curl, unzip: tools umum
RUN apk add --no-cache \
    build-base \
    linux-headers \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    supervisor

# Instalasi ekstensi PHP yang umum digunakan oleh Laravel
RUN docker-php-ext-install \
    pdo_mysql \
    bcmath \
    gd \
    zip \
    exif \
    pcntl \
    mbstring \
    xml

# Instalasi Composer (dependency manager untuk PHP) secara global
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy file composer terlebih dahulu untuk caching layer
COPY composer.json composer.lock ./

# Install dependensi vendor tanpa dev, optimasi autoloader
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Copy seluruh kode aplikasi ke working directory
COPY . .

# Atur kepemilikan file/folder agar dapat ditulis oleh web server
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 untuk PHP-FPM
EXPOSE 9000

# Perintah default untuk menjalankan PHP-FPM
CMD ["php-fpm"]
