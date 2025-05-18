#!/bin/bash
set -e

cd /var/www/html/api

# Garante que a base existe (ou zera no primeiro load)
if [ -f database/database.sqlite ]; then
  echo "Zerando banco SQLite..."
  rm database/database.sqlite
fi

echo "Criando banco SQLite..."
touch database/database.sqlite

# Garante que .env existe
if [ ! -f .env ]; then
  echo "Gerando .env..."
  cp .env.example .env
  php artisan key:generate
fi

# Permissões
chown -R www-data:www-data .
chmod -R 755 .

# Roda migrations
php artisan migrate --force

# Roda seed apenas na primeira vez
if [ ! -f storage/seeded.flag ]; then
  echo "Rodando seeders..."
  php artisan db:seed --force
  touch storage/seeded.flag
fi

# Inicia o Apache
exec apache2-foreground
