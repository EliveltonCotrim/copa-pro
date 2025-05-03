<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Ambiente Docker

1 - Ativar containers do Docker:
```
docker-compose up -d
```

2 - Executar o comando abaixo para criar a pasta ```vendor```:
```
docker-compose exec laravel.test composer install
```

3 - Executar o comando abaixo para criar o arquivo ```.env```:
```
docker-compose exec laravel.test cp .env.example .env  
```

4 - Comando para gerar valor de chave criptográfica para o trecho ```APP_KEY=``` do arquivo ```.env```:
```
docker-compose exec laravel.test php artisan key:generate
```

5 - No arquivo ```.env```, cole o seguinte trecho de código para conectar a aplicação ao banco de dados do Docker:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=copa-pro
DB_USERNAME=sail
DB_PASSWORD=password
```

8 - Execute o comando abaixo para a criação das ```migrations``` do banco de dados:
```
docker-compose exec laravel.test php artisan migrate
```

9 - Executar o comando abaixo para a criação das ```Seeders``` do banco de dados:
```
docker-compose exec laravel.test php artisan db:seed
```

10 - Executar comando abaixo para instalar dependências JavaScript:
```
docker-compose exec laravel.test npm install
```

11 - Executar comando abaixo para compilar e otimizar componentes para produção:
```
docker-compose exec laravel.test npm run build
```

12 - Lembre-se de desativar os containers do Docker antes de fechar a aplicação (Isso evita conflitos de containers em uso em outros projetos que usam o Docker):
```
docker-compose down
```

### Painel para ambiente Docker
- copa-pro (Aplicação web): http://localhost:8000
- phpMyAdmin: http://localhost:8081


