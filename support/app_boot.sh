vendor/bin/heroku-php-nginx \
  -C support/nginx.inc.conf \
  -F support/php-fpm.inc.conf \
  -i support/php.ini \
public/
