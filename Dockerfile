FROM php:8.2-apache

# 必要なPHP拡張をインストール
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    && docker-php-ext-install pdo pdo_mysql

# Composer をインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Apacheの設定
RUN a2enmod rewrite

# 作業ディレクトリを設定
WORKDIR /var/www/html

# プロジェクトファイルをコピー
COPY . .

# Composer install
RUN composer install --no-dev --optimize-autoloader
