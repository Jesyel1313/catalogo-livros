# Catálogo de Livros

Este projeto consiste em um simples catálogo, que permite a manipulação de registros de livros, autores e categorias.

## Pre-requisitos

* PHP >= 8.2.0
* Composer >= 2.5.4
* Laravel >= 10.0.0
* MySQL >= 15.1 / MariaDB >= 10.11.2

## Instalação

Antes de tudo, vamos criar um banco de dados para a persistência dos dados da API.

Com o MySQL/MariaDB instalado em sua máquina, acesse o SGBD usando o seguinte comando (substituir com suas credenciais):

```shell
mysql -u [username] -p
```

Após acessar o SGBD, crie um novo banco de dados com um nome de sua escolha:

```shell
create database [name];
```

Com o novo banco de dados criado, é necessário inserir os dados de conexão no arquivo `.env`. As seguintes diretivas devem ser configuradas, de acordo com os seus dados de acesso:

```shell
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=[dbname]
DB_USERNAME=[username]
DB_PASSWORD=[password]
```

Após configurarmos o banco de dados, iremos instalar as depedências do projeto utilizando o Composer:

```shell
composer install
```

Agora, podemos então executar as _migrations_, para criar todas as tabelas necessárias para o funcionamento da API:

```shell
php artisan migrate
```

Se precisar, em algum momento, limpar e recriar as tabelas (por completo), execute:

```shell
php artisan migrate:refresh
```

**Esta etapa é opcional.** Caso você queira popular as tabelas, para fazer testes e não ter que criar todos os registros manualmente, podemos semeá-las com o seguinte comando:

```shell
php artisan db:seed
```

Este comando cria:

* 100 registros de categorias;
* 100 registros de autores;
* 100 registros de livros (relacionados com os autores e as categorias);
* 1 registro de usuário (para realizar a autenticação com a API).

Por fim, podemos servir a API para ser consumida:

```shell
php artisan serve
```

## Utilização

> Os detalhes de utilização desta API estão presentes no arquivo [openapi.yaml](openapi.yaml). A documentação está em conformidade com o padrão OpenAPI 3.0 e foi utilizado o [Swagger Editor](https://editor.swagger.io) para a escrita, bem como visualização.

> A comunicação com a API ocorre exclusivamente por meio do formato **JSON** e a autenticação utiliza método **JWT**.

> Todas as requisições possuem validação e tratamento de erro simples. As mensagens de erro são retornadas de acordo com o código de status HTTP adequado.

As credenciais do usuário semeado para testes são:

* **E-mail:** john.doe@example.test
* **Senha:** 12345678

Abaixo estão alguns exemplos de requisições que podem ser realizadas. No fim deste documento, está a relação completa de _endpoints_ da API e suas respectivas descrições.

> O endereço base padrão para as requisições é: `http://localhost:8000/api/v1`.

> Apesar de toda a comunicação da API ser realizada em JSON, é recomendável enviar o cabeçalho `Accept: application/json` nas requisições, para forçar o corpo da resposta neste formato. Visto que o Laravel pode retornar um formato diferente em rotas internas (como a de erro 401, por exemplo).

---

Para criar um usuário manualmente, pode-se realizar a seguinte requisição:

```http
POST /users
```

```json
{
  "name": "John Doe",
  "email": "john@test.test",
  "password": "12345678"
}
```

---

Para resgatar o token de acesso:

```http
POST /auth
```

```json
{
  "email": "john@test.test",
  "password": "12345678"
}
```

Obtendo a resposta:

```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3YxL2F1dGgiLCJpYXQiOjE2ODAwMzAzNjYsImV4cCI6MTY4MDAzMzk2NiwibmJmIjoxNjgwMDMwMzY2LCJqdGkiOiJZeE9IRW94dWE2aDRnOTNlIiwic3ViIjoiMyIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.i22fh4M0AvZNg_QqqGxOqBU7nzx_nyM_zERCPqZBSxI",
  "token_type": "bearer",
  "expires_in": 3600
}
```

---

Listando os livros registrados **(é necessário fornecer o token de acesso)**:

```http
GET /books

Authorization: Bearer [token]
```

Obtendo a seguinte coleção:

```json
[
  {
    "id": 89,
    "name": "Wuthering Heights",
    "isbn": "123-1-23-123456-1",
    "author": {
      "id": 2,
      "name": "Emilly Bronte",
      "created_at": "2023-03-28T17:11:42.000000Z",
      "updated_at": "2023-03-28T17:11:42.000000Z"
    }
  }
]
```

Por padrão, é retornado um conjunto de 20 registros. É possível passar parâmetros opcionais para ordenar os livros e controlar a paginação. Por exemplo:

* `start=10` (padrão é 0);
* `limit=5` (padrão é 20);
* `order=desc` (padrão é asc);
* `order_by=name` (padrão é _id_, e também é possível por _created_at_).

O _endpoint_ com os parâmetros ficaria:

```http
GET /books?start=10&limit=5&order=desc&order_by=name
```

---

Resgatando os detalhes de um livro específico **(é necessário fornecer o token de acesso)**:

```http
GET /books/101

Authorization: Bearer [token]
```

A resposta apropriada:

```json
{
  "id": 89,
  "name": "Wuthering Heights",
  "pages": 400,
  "year": 1800,
  "isbn": "123-1-23-123456-1",
  "synopsis": "Teste de sinopse!",
  "created_at": "2023-03-28T17:36:39.000000Z",
  "updated_at": "2023-03-28T17:36:39.000000Z",
  "author": {
    "id": 2,
    "name": "Emilly Bronte",
    "created_at": "2023-03-28T17:11:42.000000Z",
    "updated_at": "2023-03-28T17:11:42.000000Z"
  },
  "category": {
    "id": 3,
    "name": "Voluptatibus",
    "created_at": "2023-03-28T17:11:42.000000Z",
    "updated_at": "2023-03-28T17:11:42.000000Z"
  }
}
```

---

Registrando um novo livro **(é necessário fornecer o token de acesso)**:

**Obs.:** Os ID's de autor e categoria precisam existir no banco de dados.

```http
POST /books

Authorization: Bearer [token]
```

```json
{
  "name": "Wuthering Heights",
  "author_id": 2,
  "category_id": 3,
  "pages": 400,
  "year": 1800,
  "isbn": "123-1-23-123456-1",
  "synopsis": "Here's the synopsis of the book..."
}
```

Em caso de sucesso, a resposta será conforme a seguir:

```json
{
  "message": "Book created successfully!",
  "id": 89
}
```

---

Atualizando um livro **(é necessário fornecer o token de acesso)**:

**Obs.:** Os ID's de autor e categoria precisam existir no banco de dados.

```http
PUT /books/89

Authorization: Bearer [token]
```

```json
{
  "name": "Wuthering Heights",
  "author_id": 2,
  "category_id": 3,
  "pages": 400,
  "year": 1800,
  "isbn": "123-1-23-123456-1",
  "synopsis": "Synopsis test..."
}
```

E em caso de sucesso, um objeto parecido com este será retornado:

```json
{
  "message": "Book updated successfully!"
}
```

---

Para remover um livro registrado, basta fazer a seguinte requisição **(é necessário fornecer o token de acesso)**:

```http
DELETE /books/89

Authorization: Bearer [token]
```

Com a seguinte resposta:

```json
{
  "message": "Record removed successfully!"
}
```

## Endpoints

### Usuários

| **Método** | **Rota** | **Descrição** |
| ---------- | -------- | ------------- |
| POST   | /users | Cria um novo usuário |

### Autenticação

| **Método** | **Rota** | **Descrição** |
| ---------- | -------- | ------------- |
| POST   | /auth | Gera um novo token de acesso |
| PUT    | /auth | Renova o token de acesso |
| DELETE | /auth | Remove o token de acesso |
| GET    | /auth | Resgata o usuário autenticado | 

### Livros

| **Método** | **Rota** | **Descrição** |
| ---------- | -------- | ------------- |
| POST   | /books | Insere um novo livro |
| GET    | /books | Resgata um conjunto de livros | 
| GET    | /books/{id} | Resgata um livro | 
| PUT    | /books/{id} | Atualiza um livro |
| DELETE | /books/{id} | Remove um livro |

### Autores

| **Método** | **Rota** | **Descrição** |
| ---------- | -------- | ------------- |
| POST   | /authors | Insere um novo autor |
| GET    | /authors | Resgata um conjunto de autores | 
| GET    | /authors/{id} | Resgata um autor | 
| PUT    | /authors/{id} | Atualiza um autor |
| DELETE | /authors/{id} | Remove um autor |

### Categorias

| **Método** | **Rota** | **Descrição** |
| ---------- | -------- | ------------- |
| POST   | /categories | Insere uma nova categoria |
| GET    | /categories | Resgata um conjunto de categorias | 
| GET    | /categories/{id} | Resgata uma categoria | 
| PUT    | /categories/{id} | Atualiza uma categoria |
| DELETE | /categories/{id} | Remove uma categoria |

Os detalhes destes _endpoints_ podem ser encontados na [documentação completa do Swagger](openapi.yaml).

## Licença

Este projeto é de código aberto e está licenciado sob a [Licença MIT](LICENSE.md)