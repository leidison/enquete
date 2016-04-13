# Enquete
CRUD de enquete, feito na linguagem PHP. O sistema utiliza Symfony 2.8, Doctrine2, AngularJS 1.5, Bootstrap 3, MySQL e autenticação via OAuth2.

## Banco de dados

O SGBD escolhido original para o projeto foi o MySQL. Porém, a API funcionará com qualquer base de dados relacional escolhida.
[Link para o SQLScript](/banco/Script.sql) do projeto.

## Instalação do projeto

Como o projeto utiliza Composer e Bower para genciar os pacotes da API e do front-end,consecutivamente, será necessário executar os passos abaixo:

Acesse a [documentação do Bower](http://bower.io/) para saber como instalar. Acesse também a [documentação do Composer](https://www.getcomposer.org/).

Instalando dependências da API:

    DIRETORIO-RAIZ/api: composer install

Instalando dependências do front-end:

    DIRETORIO-RAIZ/front-end: bower install

# Testes Funcionais

O projeto utiliza o PHPUnit para executar os testes funcionais na API. Acesse a [documentação do PHPUnit](https://phpunit.de/) para saber como instalar.

Executanto testes na API

    DIRETORIO-RAIZ/api: phpunit -c app --process-isolation --stop-on-failure
