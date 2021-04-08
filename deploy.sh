#!/bin/sh
name='pars-api'

export PATH=/opt/plesk/php/7.4/bin:$PATH:$HOME/bin

if [ ! -d "$name" ]; then
  git clone https://github.com/pars-framework/$name
  php /usr/lib64/plesk-9.0/composer.phar install --no-dev --no-interaction &>deploy-new.log
fi

cd $name
php /usr/lib64/plesk-9.0/composer.phar update --no-dev --no-interaction &>deploy.log

logger -f deploy.log
cd ..
cp -rf pars-admin/* . | logger
if [ -f $name/pars-update ]; then
  php /usr/lib64/plesk-9.0/composer.phar run-script pars-update | logger
  rm $name/pars-update
fi


