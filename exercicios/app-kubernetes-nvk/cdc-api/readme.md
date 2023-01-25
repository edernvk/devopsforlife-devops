# CDC Digital - cdc-api

## Requisitos

- Instalar o **Laragon** [https://laragon.org/download/](https://laragon.org/download/) (recomendado a versão *full*)
- Instalar o **Composer** [https://getcomposer.org/download/](https://getcomposer.org/download/)
- Instalar o [**Insomnia**](https://insomnia.rest/download/) (recomendado) ou o [**Postman**](https://www.postman.com/downloads/) para testar a utilização da API


## Setup

1 - Copiar o projeto do repositório na sua máquina:
  + `git clone https://github.com/penzebr/cdc-api.git`

2 - Entrar na pasta do projeto:
  + `cd cdc-api`

3 - Instalar dependências:
  + `composer install`

4 - Criar um banco de dados com o gerenciador de banco de dados (utilizando o HeidiSQL que vem com o Laragon ou qualquer outro client de banco de dados).
  + nome do banco de dados: cdcdigital
  + geralmente a configuração padrão para o usuário `root`:
    - usuário: **root**
    - senha: *(vazio)* 

5 - Copiar conteúdo do arquivo `.env.example` para um novo arquivo `.env` (configure as informações conforme seu ambiente local)

6 - Execute os comandos:
  + `php artisan key:generate` (cria uma chave única que é usada no seu projeto local, esse comando atualiza automaticamente a entrada `APP_KEY=` no arquivo `.env` )
  + `php artisan storage:link` (cria um link simbólico da pasta `storage` para ser visível publicamente no servidor)

7 - Rodar comando para criar e popular o banco de dados com informações iniciais aleatórias:
  + `php artisan migrate:fresh --seed`

8 - Rodar um servidor php com o Laravel localmente (não recomendado):
  + `php artisan serve` (por padrão, acessar em `localhost:8000`) (AVISO: esse método deve ser usado apenas em última instância! Dê preferência à configuração do virtual-host com o endereço `cdc-api.test` pois assim não precisará ficar iniciando o server toda hora e os arquivos de configuração de ambiente (`environment`) do front-end poderão ser compartilhados entre todos do time com a mesma configuração padronizada)


## Comandos principais

- Atualizar arquivo de autoload das classes
  + `composer dumpautoload`

- Reestruturar o banco de dados do zero e popular as tabelas:
  + `php artisan migrate:fresh --seed`

- Gerar Model e Migration da tabela ao mesmo tempo:
  + ATENÇÃO: este comando cria as Migrations com o nome do Model no plural, por exemplo, o comando abaixo geraria uma Migration para a criação da tabela 'ciclos_vidas'. Se este não for o comportamento esperado da sua aplicação, tenha atenção em renomear as devidas classes e arquivos.
    - `php artisan make:model Models\CiclosVida --migration`
    - `php artisan make:model Models\CiclosVida -m`

- Gerar Migration:
  + `php artisan make:migration create_users_table --create=users`
  + `php artisan make:migration add_votes_to_users_table --table=users`

- Gerar Seeder do Model:
  + `php artisan make:seeder CiclosVidaSeeder`
    - talvez seja necessário atualizar os dados do Autoloder do Composer: `composer dumpautoload`
  + `php artisan db:seed --class=UsersTableSeeder` (para executar exclusivamente um seed)

- Gerar Factory do Model:
  + `php artisan make:factory CiclosVidaFactory --model=Models\CiclosVida`

- Gerar API Resource
  + `php artisan make:resource CiclosVidaResource`

- Gerar Resource Controller
  + `php artisan make:controller ContatosController --resource`

