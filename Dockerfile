# 基本イメージ
FROM php:8.2-apache

# Composer をインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 拡張インストール（PDO for MySQL）
RUN docker-php-ext-install pdo pdo_mysql

# 作業ディレクトリを設定
WORKDIR /var/www/html

# プロジェクトファイルをコピー
COPY . .

# Composer で依存をインストール
RUN composer install --no-dev --optimize-autoloader

# .htaccessを有効にする
RUN a2enmod rewrite
