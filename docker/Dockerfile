FROM php:8.3-apache

# Установка расширений PHP
RUN docker-php-ext-install -j$(nproc) pdo_mysql

# Обновление и установка необходимых пакетов
RUN apt-get update && apt-get upgrade -y && apt-get install -y git

# Установка зависимостей для zip
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -s /usr/local/bin/composer /usr/bin/composer

# Установка рабочего каталога
WORKDIR /var/www/html

# Включение модуля Apache rewrite
RUN /usr/sbin/a2enmod rewrite

# Копирование конфигурации Apache
COPY default.conf /etc/apache2/sites-available/000-default.conf

# Установка переменной окружения PATH
ENV PATH="/usr/local/bin:${PATH}"
