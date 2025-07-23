# 基本イメージ
FROM php:8.2-apache

# 拡張インストール（PDO for MySQL）
RUN docker-php-ext-install pdo pdo_mysql

# Apacheのドキュメントルートを /var/www/html に設定
COPY . /var/www/html/

# .htaccessを有効にする
RUN a2enmod rewrite
