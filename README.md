# Agenda de Contatos ShipSmart - Laravel API

API RESTful para gerenciamento de contatos, construída com Laravel 8+, Vue 3 (frontend separado), SQLite, e documentada com Swagger. A aplicação está preparada para rodar via Docker.

## 🚀 Tecnologias Utilizadas

- PHP 8.1
- Laravel 8+
- SQLite (banco de dados leve para desenvolvimento)
- Docker + Docker Compose
- Mail (Mailhog ou log)
- Swagger (L5-Swagger)
- PHPUnit (TDD)
- Vue 3 (frontend separado)

---

## 📦 Como rodar o projeto com Docker

### 1. Clone o repositório
```bash
git clone https://github.com/gerlisson/shipsmart-test.git
cd shipsmart-test
```

### 2. Configure as permissões e dependências
Crie os arquivos necessários se ainda não existirem:
```bash
touch api/database/database.sqlite
cp api/.env.example api/.env
```

### 3. Suba os containers
```bash
docker-compose up -d --build
```

> O container será iniciado e executará automaticamente:
> - Criação do banco SQLite
> - Migrações com Artisan
> - Seed inicial (somente na primeira vez)
> - Geração da chave `APP_KEY`


### 4. Acesse a aplicação
- **API:** http://localhost:8000/api/contacts
- **Swagger:** http://localhost:8000/api/documentation
- **Mailhog (se adicionado):** http://localhost:8025

---

## 🖥️ Rodar o frontend Vue 3

### 1. Acesse a pasta do frontend
```bash
cd front
```

### 2. Instale as dependências
```bash
npm install
```

### 3. Rode o servidor de desenvolvimento
```bash
npm run serve
```

### 4. Acesse no navegador:
```
http://localhost:8081
```

> Certifique-se de que o frontend está apontando para `http://localhost:8000/api` nas requisições.

---

## 🧪 Rodar os testes

Dentro do container:
```bash
docker exec -it shipsmart_api bash
php artisan test
```

---

## 📚 Documentação Swagger

Documentação gerada automaticamente com L5-Swagger.

- Para atualizar a documentação manualmente:
```bash
docker exec -it laravel_app bash
php artisan l5-swagger:generate
```

- Acesse: http://localhost:8000/api/documentation

---

## 💡 Observações

- As validações estão implementadas com `FormRequest`
- As regras de negócio estão desacopladas em `Service`
- A aplicação segue princípios SOLID e Clean Architecture
- Testes de unidade cobrem `ContactService` e `Controller`

---
