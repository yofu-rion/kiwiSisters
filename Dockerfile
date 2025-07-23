# 基本イメージ
FROM php:8.2-apache

# Composer をインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# GD拡張と必要な依存ライブラリをインストール
RUN apt-get update && \
    apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# 作業ディレクトリを設定
WORKDIR /var/www/html

# プロジェクトファイルをコピー
COPY . .

# Composer install
RUN composer install --no-dev --optimize-autoloader

# .htaccessを有効にする
RUN a2enmod rewrite
