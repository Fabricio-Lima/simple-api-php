# REST API DESENVOLVIDA COM PHP 8.2 E MYSQL

**Pré-requisitos:** PHP, Composer, MySQL

## Começando

Clone o projeto com os comandos abaixo:

```
git clone https://github.com/Fabricio-Lima/simple-api-php
cd simple-api-php
```

### Configurando ambiente

Crie o banco de dados MySQL:

```
mysql -uroot -p
CREATE DATABASE api_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'api_user'@'localhost' identified by 'password';
GRANT ALL on api_db.* to 'api_user'@'localhost';
quit
```

Copie e edite o `.env` e insira as credencias de acesso do Banco de Dados

```
cp .env.example .env
```

Instalando as dependências e iniciando o servidor PHP:

```
composer install
cd public
php -S 127.0.0.1:8000
```

## Licença

Apache 2.0, veja [LICENSE](LICENSE).
