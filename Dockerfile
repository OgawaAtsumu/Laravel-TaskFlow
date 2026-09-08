# ==========================================
# 1. Node.js 繧ｹ繝・・繧ｸ・医ヵ繝ｭ繝ｳ繝医お繝ｳ繝峨ン繝ｫ繝会ｼ・# ==========================================
FROM node:20-alpine AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# ==========================================
# 2. PHP / Apache 繧ｹ繝・・繧ｸ
# ==========================================
FROM php:8.3-apache

# 蠢・ｦ√↑繝代ャ繧ｱ繝ｼ繧ｸ縺ｨ PHP 諡｡蠑ｵ繝｢繧ｸ繝･繝ｼ繝ｫ縺ｮ繧､繝ｳ繧ｹ繝医・繝ｫ
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer 縺ｮ繧､繝ｳ繧ｹ繝医・繝ｫ
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Apache 縺ｮ險ｭ螳・ DocumentRoot 繧・/var/www/html/public 縺ｫ螟画峩縺励［od_rewrite 繧呈怏蜉ｹ蛹・ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

WORKDIR /var/www/html

# 繧｢繝励Μ繧ｱ繝ｼ繧ｷ繝ｧ繝ｳ繧ｳ繝ｼ繝峨・繧ｳ繝斐・
COPY . .

# Node.js 繧ｹ繝・・繧ｸ縺ｧ繝薙Ν繝峨＆繧後◆ Vite 繧｢繧ｻ繝・ヨ繧偵さ繝斐・
COPY --from=node_builder /app/public/build ./public/build

# 譛ｬ逡ｪ逕ｨ Composer 萓晏ｭ倥ヱ繝・こ繝ｼ繧ｸ縺ｮ繧､繝ｳ繧ｹ繝医・繝ｫ
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 繧ｹ繝医Ξ繝ｼ繧ｸ縺ｨ繧ｭ繝｣繝・す繝･縺ｮ繝代・繝溘ャ繧ｷ繝ｧ繝ｳ險ｭ螳・RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 繧ｨ繝ｳ繝医Μ繝昴う繝ｳ繝医せ繧ｯ繝ｪ繝励ヨ縺ｮ險ｭ螳・COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
