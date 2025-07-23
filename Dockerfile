FROM php:8.2-apache

# Composer をインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 拡張と依存ライブラリをインストール
RUN apt-get update && \
    apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        zip unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# 作業ディレクトリ設定
WORKDIR /var/www/html

# 依存ファイルを先にコピーしてキャッシュ効かせる
COPY composer.json composer.lock ./

# Composer install 実行（失敗時ログを出す）
RUN composer install --no-dev --optimize-autoloader -vvv

# 残りのファイルコピー
COPY . .

# Apacheのmod_rewrite有効化
RUN a2enmod rewrite
