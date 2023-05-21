# REST API DESENVOLVIDA COM PHP 8.2 E MYSQL

**Pré-requisitos:** PHP, Composer, MySQL

## Começando

Clone o projeto com os comandos abaixo:

```
git clone https://github.com/Fabricio-Lima/simple-api-php
cd simple-api-php
```

### Configurando ambiente

Crie e execute o banco de dados MySQL, da maneira de desejar.


Copie e edite o `.env` e insira as credencias de acesso do Banco de Dados

```
cp .env.example .env
```

Instalando as dependências:

```
composer install
```

Executando SEED do Banco de Dados:

```
php dbseed.php
```

Inciciando servidor PHP:

```
php -S 127.0.0.1:8000 -t api
```

Endpoints:

- Consumir saldo dos Recursos e dos Clubes (Use o método POST)

```
/clube/consumir
```

- Inserir novo Clube (Use o método POST) e lista todos os Clubes (Use o método GET)
```
/clube
```

## Licença

Apache 2.0, veja [LICENSE](LICENSE).
