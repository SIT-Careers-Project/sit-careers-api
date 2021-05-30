### SIT Careers API
----
##### Config your machine for 1st Time
1. Install PHP >= 7.0.0
2. Install [Composer](https://getcomposer.org/)
    > ##### For windows dowload Download and run [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe)
    > ##### For Mac OS you can use the command line in this [Link](https://duvien.com/blog/installing-composer-mac-osx)

3. Install Laravel 
```bash
composer global require laravel/installer
```

##### Start Project
1. if you must to migrate and seed you can use below command
```bash
php artisan migrate --seed
```
2. Start server or Start project
```bash
php artisan serve
```

> Don't forget change .env
-----

##### Start Project by docker-compose
###### You can use docker-compose with development
1. For the first time.
```bash
docker-compose -f docker-compose.local.yml up --build
```
2. Next time.
```bash
docker-compose -f docker-compose.local.yml up
```
3. If you install some libs and want use composer. `Just restart composer container` and wait.
```bash
docker restart sit-careers-composer
```
If you want to exec to container api use
```bash
docker exec -it sit-careers-api bash
```
###### Testing on local
1. Copy .env.example to .env.testing and Change database
```bash
cp .env.example .env.testing

DB_HOST=db
DB_DATABASE=sitcareers_testing
```
> Don't forget update DB_PASSWORD

2. Exec to container api
```bash
docker exec -it sit-careers-api bash
```
3. Run test
```bash
php artisan test --env=testing
```

> Note: DB_DATABASE on local have 2 databases: `sitcareers`, `sitcareers_testing`
-----
Enjoy !! ✌
