# 1. PHP公式イメージをベースに使用
FROM php:8.2-fpm

# 2. システムパッケージのインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# 3. Composer のインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4. 作業ディレクトリの設定
WORKDIR /var/www

# 5. アプリケーションコードのコピー
COPY . .

# 6. 環境ファイルのコピー（production 用を .env に）
RUN cp .env.production .env

# 7. Laravel 用フォルダのパーミッション調整と key:generate
RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan key:generate --force

# 8. ポート公開
EXPOSE 8000

# 9. Laravel サーバー起動
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
