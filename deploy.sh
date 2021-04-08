#!/bin/sh
name='pars-api'

export PATH=/opt/plesk/php/7.4/bin:$PATH:$HOME/bin

git clone https://github.com/pars-framework/$name
cd $name
php /usr/lib64/plesk-9.0/composer.phar install --no-dev --no-interaction &>deploy.log
logger -f deploy.log
cd ..
cp -rf pars-admin/* . | logger
rm -rf $name
