FROM php:8.1-apache

# Instala pacotes necessários
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Ativa o módulo de reescrita do Apache
RUN a2enmod rewrite

COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Define o diretório de trabalho
WORKDIR /var/www/html/api

# Copia os arquivos do projeto
COPY . /var/www/html

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Permissões (ajuste conforme necessário)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Adiciona script de inicialização
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
