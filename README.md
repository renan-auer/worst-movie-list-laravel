
# Worst Movie List

Bem vindo ao Worst Movie List API. Essa é uma API desenvolvida em PHP e o framework Laravel.


## Instalação

Instale o composer através do link

```bash
https://getcomposer.org/
```

Após isso, clone o projeto com o comando:

```bash
https://github.com/renan-auer/worst-movie-list-laravel
```

Entre na pasta do projeto e faça a instação das dependências com o comando:

```bash
  composer install
```

Depois execute o seguinte comando para criar as tabelas necessárias

```bash
  php artisan migrate:fresh
```

Depois execute o seguinte comando para iniciar a aplicação Laravel:

```bash
  php artisan serve
```

O endpoint implementado está disponível no seguinte endereço:

```bash
http://localhost:8000/api/movies/max-min-win-interval-for-producers
```

Caso deseje testar com outro arquivo CSV, altere substitua o arquivo localizado em:

```bash
.\storage\app\movielist.csv
```

## Rodando os testes

Para rodar os testes, rode o seguinte comando

```bash
  php artisan test
```

