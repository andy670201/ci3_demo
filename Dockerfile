FROM php:8.2-apache

# 安裝系統套件（加入 libonig-dev）
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libsqlite3-dev \
    libonig-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 安裝 PHP 擴充
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    zip \
    mbstring

# 開啟 rewrite
RUN a2enmod rewrite

# 允許 .htaccess 覆寫（CI3 必要）
RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf