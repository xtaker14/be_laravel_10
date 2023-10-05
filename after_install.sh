#!/bin/sh
cd /var/www/html/tms-app
sudo chown ec2-user -R /var/www/html/tms-app
sudo chgrp ec2-user -R /var/www/html/tms-app
sudo chmod 0777 -R /var/www/html/tms-app/public
sudo chmod 0777 -R /var/www/html/tms-app/storage
php composer.phar update
php composer.phar install
php composer.phar dump-autoload -o
php artisan optimize:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan config:clear & php artisan cache:clear
php artisan storage:link
sudo service supervisord restart
sudo service httpd restart
