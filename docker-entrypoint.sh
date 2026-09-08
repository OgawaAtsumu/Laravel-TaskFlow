#!/bin/bash
set -e

# Render 縺ｮ迺ｰ蠅・､画焚 PORT 縺ｫ蟇ｾ蠢懶ｼ医ョ繝輔か繝ｫ繝・ 80・・PORT=${PORT:-80}
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf

# 險ｭ螳壹く繝｣繝・す繝･縺ｨ繝槭う繧ｰ繝ｬ繝ｼ繧ｷ繝ｧ繝ｳ縺ｮ螳溯｡・php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true
php artisan migrate --force || true

exec apache2-foreground
