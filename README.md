## Language & Framework Used:
1. PHP >= 8.1
1. Laravel 10
2. Mysql 8

## REQUIREMENTS
1. Composer version 2.x
1. Node v16.x.x 
2. Npm 8.x.x 

## How to Run:
1. Clone this project
2. Create new database 
3. Rename file **`.env.example`** to **`.env`** (Note: Make sure you have hidden files shown on your system) 
4. Run Composer Install (**`COMPOSER_MEMORY_LIMIT=-1`** is optional)
```
COMPOSER_MEMORY_LIMIT=-1 composer install
```
5. Run npm install 
```
npm install && npm run dev
```
6. Create docker resource
```
docker-compose up -d
```
7. Running fresh database migrations
```
php artisan migrate:fresh --env=local
```
8. Running database seed (**`--class=InitUserSeeder`** is optional for testing user SA)
```
php artisan db:seed --env=local
php artisan db:seed --class=InitUserSeeder 
```
9. Run generate key for data encryption (data encryption in various security contexts, including hashing passwords and encrypting sensitive data within sessions)
```
php artisan key:generate
php artisan jwt:secret 
```
10. Refresh the app from cache bugs **(optional)**
```
php artisan optimize:clear; php artisan cache:clear; php artisan config:clear; php artisan view:clear; composer dump-autoload 
```
11. Laravel IDE Helper (optional to generate IDE Helper files that repair and enhance the capabilities of Integrated Development Environments such as PHPStorm, VS Code, or other IDEs)
```
php artisan ide-helper:generate 
```
12. Running application test **(Feature & Unit Test)**
```
php artisan test --env=testing
```
13. Running application
```
php artisan serve --env=local
```
14. Running Scheduler Task On Server
```
php artisan schedule:run
```
15. Running Scheduler Task Locally
```
php artisan schedule:work
```
16.  Running static analytic for errors
```
vendor/bin/phpstan analyse --autoload-file=_ide_helper.php app  --level 1 --memory-limit 512M
```

## SOURCES

1. https://github.com/barryvdh/laravel-ide-helper 
2. https://jwt-auth.readthedocs.io/en/develop/laravel-installation 
3. https://spatie.be/docs/laravel-permission/v5/introduction 
4. https://docs.laravel-excel.com/3.1/getting-started/installation.html 
5. https://laravel.com/docs/10.x/telescope 
6. https://packagist.org/packages/league/flysystem-aws-s3-v3 
7. https://github.com/aws/aws-sdk-php-laravel 
8. https://www.codesolutionstuff.com/how-to-send-sms-using-twilio-in-laravel 
9.  https://github.com/fruitcake/laravel-cors/blob/master/readme.md
