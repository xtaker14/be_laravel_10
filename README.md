## Language & Framework Used:
1. PHP >= 8.2
1. Laravel 10
2. Mysql 8

## REQUIREMENTS
1. Composer version 2.x
1. Node >= v16.x.x 
2. Npm >= 8.x.x 

## How to Run:
- Clone this project
- Create new database 
- Update File
   - Rename file **`.env.example`** to **`.env`** (Note: Make sure you have hidden files shown on your system)
   - Add new file telescope.sqlite in directory `{root}\database` 
- Run Composer Install (**`COMPOSER_MEMORY_LIMIT=-1`** is optional)
```
COMPOSER_MEMORY_LIMIT=-1 composer install
```
- Run npm install 
```
npm install && npm run dev
```
- Running application initialization (database migrations and symbolic link `public/storage` to `storage/app/public`)
```
php artisan migrate:fresh --env=local
php artisan storage:link
```
- Running database seed (**`--class=InitSeeder`** is optional for testing user SA)
```
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder
```
- Run generate key for data encryption (data encryption in various security contexts, including hashing passwords and encrypting sensitive data within sessions)
```
php artisan key:generate
php artisan jwt:secret
```
- Refresh the app from cache bugs **(optional)**
```
php artisan optimize:clear; php artisan cache:clear; php artisan config:clear; php artisan view:clear; composer dump-autoload 
```
- Laravel IDE Helper (optional to generate IDE Helper files that repair and enhance the capabilities of Integrated Development Environments such as PHPStorm, VS Code, or other IDEs)
```
php artisan ide-helper:generate 
```
- Running application test **(Feature & Unit Test)**
```
php artisan test --env=testing
```
- Running application
```
php artisan serve --env=local
```
- Running Scheduler Task On Server
```
php artisan schedule:run
```
- Running Scheduler Task Locally
```
php artisan schedule:work
```
- Running static analytic for errors
```
vendor/bin/phpstan analyse --autoload-file=_ide_helper.php app --level 1 --memory-limit 512M
```
- Generate ERD database (optional, to check All relation tables)
```
php artisan generate:erd erd_database.png --format=png
```
- Running initialization job queue
```
php artisan queue:table (buat file migrasi job queue)
php artisan queue:failed-table (buat file migrasi job failed queue)
php artisan migrate (membuat job queue table di db untuk data job queue yg gagal)
php artisan queue:work --tries=3 (menjalankan semua job queue dengan 3x percobaan)
* * * * * cd /path-root-project && php artisan schedule:run >> /dev/null 2>&1 (menjalankan scheduler)
```
```
QUEUE_CONNECTION=database (ubah .env)
php artisan config:clear; (command)
```

## DOCS
1. user > role > privilege > feature > permission

2. User:
   - Seorang **`User`** berelasi dengan satu **`Role`** melalui kolom **`role_id`**.

3. Role:
   - Sebuah **`Role`** dapat memiliki banyak **`User`**.
   - Sebuah **`Role`** dapat memiliki banyak **`Privilege`**.

4. Permission:
   - Sebuah **`Permission`** dapat memiliki banyak **`Menu`**.
   - Sebuah **`Permission`** dapat memiliki banyak **`Privilege`**.

5. Feature:
   - Sebuah **`Feature`** dapat memiliki banyak **`Menu`**.
   - Sebuah **`Feature`** dapat memiliki banyak **`Privilege`**.

6. Privilege:
   - Sebuah **`Privilege`** berelasi dengan satu **`Role`**.
   - Sebuah **`Privilege`** berelasi dengan satu **`Feature`**.
   - Sebuah **`Privilege`** berelasi dengan satu **`Permission`**.

7. Menu:
   - Sebuah **`Menu`** dapat berelasi dengan **`Menu`** lain melalui **`parent_id`** yang mengindikasikan struktur hierarki menu.
   - Sebuah **`Menu`** berelasi dengan satu **`Feature`**.
   - Sebuah **`Menu`** berelasi dengan satu **`Permission`**.

## SOURCES

1. https://github.com/barryvdh/laravel-ide-helper 
2. https://jwt-auth.readthedocs.io/en/develop/laravel-installation 
3. https://spatie.be/docs/laravel-permission/v5/introduction 
4. https://docs.laravel-excel.com/3.1/getting-started/installation.html 
5. https://laravel.com/docs/10.x/telescope 
6. https://www.codesolutionstuff.com/how-to-send-sms-using-twilio-in-laravel 
7.  https://github.com/fruitcake/laravel-cors/blob/master/readme.md
8.  https://github.com/beyondcode/laravel-er-diagram-generator
