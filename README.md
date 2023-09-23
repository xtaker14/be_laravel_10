### Language & Framework Used:
1. PHP >= 8.1
1. Laravel 10
2. Mysql 8

## REQUIREMENTS
1. Composer version 2.x
1. Node v16.x.x 
1. Npm 8.x.x 
1. Yarn 1.x.x

## How To Run
1. Clone this project
2. Run Composer Install
```
composer install
```
3. Create docker resource
```
docker-compose up -d
```
4. Running fresh database migrations
```
php artisan migrate --env=local
```
5. Running database seed
```
php artisan db:seed --env=local
```
6. Running application test

Feature & Unit Test
```
php artisan test --env=testing
```

7. Running application
```
php artisan serve --env=local
```

8. Running Scheduler Task On Server
```
php artisan schedule:run
```

9. Running Scheduler Task Locally
```
php artisan schedule:work
```

10. Running static analytic for errors
```
vendor/bin/phpstan analyse --autoload-file=_ide_helper.php app  --level 1 --memory-limit 512M
```
