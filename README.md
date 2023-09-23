<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Language & Framework Used:
1. PHP >= 8.1
1. Laravel 10
2. Mysql 8

## REQUIREMENTS
1. Composer version 2.x
1. Node v16.x.x 
1. Npm 8.x.x 
1. Yarn 1.x.x

## How to Run:
1. create new database 
1. rename file **`.env.example`** to **`.env`** (Note: Make sure you have hidden files shown on your system) 
1. **`COMPOSER_MEMORY_LIMIT=-1 composer install`** from the root of the project (**`COMPOSER_MEMORY_LIMIT=-1`** is optional)
1. **`npm install && npm run dev`** from the root of the project 
1. **`yarn`** from the root of the project (If you have Yarn installed)
1. **`php artisan migrate:fresh; php artisan db:seed --class=InitUserSeeder`** from the root of the project 
1. **`php artisan key:generate`** from the root of the project (You should see a green message stating your key was successfully generated. As well as you should see the APP_KEY variable in your .env file reflected)
1. **`php artisan jwt:secret`** from the root of the project 
1. **`php artisan optimize:clear; php artisan cache:clear; php artisan config:clear; php artisan view:clear; composer dump-autoload`** from the root of the project (optional if you want to refresh the app) 
1. **`php artisan ide-helper:generate`** from the root of the project (optional)  
1. **`php artisan serve`** to Run the server

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

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
