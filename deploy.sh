#!/bin/sh
export PATH=/opt/plesk/php/7.4/bin:$PATH:$HOME/bin
if [ -d "vendor" ]; then
  rm composer.lock
  rm -rf vendor
  php /usr/lib64/plesk-9.0/composer.phar update --no-dev --no-interaction &>deploy.log
else
  php /usr/lib64/plesk-9.0/composer.phar install --no-dev --no-interaction &>deploy.log
fi
