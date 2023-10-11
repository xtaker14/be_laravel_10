#!/bin/sh
export NVM_DIR="$HOME/.nvm" # export NVM
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
cd /var/www/html/tms-app
sudo chown ec2-user -R /var/www/html/tms-app
sudo chgrp ec2-user -R /var/www/html/tms-app
sudo chmod 0777 -R /var/www/html/tms-app/storage
php composer.phar install
npm install
php composer.phar dump-autoload -o
php artisan optimize:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan config:clear & php artisan cache:clear
php artisan storage:link
sudo service supervisord restart
sudo service httpd restart
