<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Ambiente Docker

1 - Ative os containers do Docker:
```
docker-compose up -d
```

2 - Instale as dependências PHP para criar a pasta ```vendor```:
```
docker-compose exec laravel.test composer install
```

3 - Copie o arquivo de exemplo ```.env.example``` para ```.env```:
```
docker-compose exec laravel.test cp .env.example .env  
```

4 - Gere a chave de criptografia da aplicação (preenche ```APP_KEY=``` no arquivo ```.env```):
```
docker-compose exec laravel.test php artisan key:generate
```

5 - Configure a conexão com o banco de dados no arquivo ```.env``` adicionando o seguinte trecho:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=copa-pro
DB_USERNAME=sail
DB_PASSWORD=password
```

6 - Rode as ```migrations``` para criar as tabelas do banco de dados:
```
docker-compose exec laravel.test php artisan migrate
```

7 - Popule o banco de dados com registros iniciais utilizando os ```seeders```:
```
docker-compose exec laravel.test php artisan db:seed
```

8 - Instale as dependências JavaScript com o ```npm```:
```
docker-compose exec laravel.test npm install
```

9 - Compile e otimize os arquivos JavaScript para produção:
```
docker-compose exec laravel.test npm run build
```

10 - Lembre-se de desativar os containers do Docker antes de fechar a aplicação (Isso evita conflitos de containers em uso em outros projetos que usam o Docker):
```
docker-compose down
```

### Endereços de ambiente Docker
- copa-pro (Aplicação web): http://localhost:8000
- phpMyAdmin: http://localhost:8081
- Mailpit: http://localhost:8025/ 


