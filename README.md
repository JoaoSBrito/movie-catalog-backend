# 🎬 Catálogo de filmes Backend

Projeto backend para o catálogo de filmes, desenvolvido com Laravel, Docker (Laravel Sail) e integração com a API do TMDB.

## 🚀 Requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Git](https://git-scm.com/)
- Conta na [TMDB](https://www.themoviedb.org/) para gerar uma chave de API






## Como rodar o projeto

    1. Clone o repositório
```bash
    git clone -b develop https://github.com/JoaoSBrito/movie-catalog-backend.git
    cd movie-catalog-backend
```
    2. Copie o arquivo .env de exemplo
```bash
   cp .env.example .env
```
    3. Adicione sua chave da API do TMDB no .env
```bash
   TMDB_API_KEY=sua_chave_aqui
```
    4. Instale as dependências com Composer
```bash
   composer install
```
    5. Suba o ambiente com Docker (Sail)
```bash
    ./vendor/bin/sail up -d
```
    6. Gere a chave da aplicação
```bash
    ./vendor/bin/sail artisan key:generate
```
    7. Rode as migrations
```bash
    ./vendor/bin/sail artisan migrate
```
## Deploy

Para fazer o deploy desse projeto rode

```bash
  ./vendor/bin/sail up -d
```


## Rodando os testes

Para rodar os testes, rode o seguinte comando

```bash
  ./vendor/bin/sail test
```

