#!/bin/bash
## buat NPM
export NVM_DIR="$HOME/.nvm" # export NVM
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
## buat change owner sama grub
sudo chown ec2-user -R /var/www/html/tms-app ## buat change owner
sudo chgrp ec2-user -R /var/www/html/tms-app ## buat change grub
## cd ( change directory )
cd /var/www/html/tms-app ## change directory ke tempat terkait
## build sebelum run
npm install
npm install -g pm2
npm run build ## buat build
## stop dan start
/home/ec2-user/.nvm/versions/node/v16.20.2/bin/pm2 delete all ## stop pm2
/home/ec2-user/.nvm/versions/node/v16.20.2/bin/pm2 start ## start pm2
