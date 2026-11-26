FROM nginx:1.31.6@sha256:60f0d4e986561e08804f75c211dcaf7fc5455699eada29f42cbe8a0a7253dd86

COPY --from=mediawiki /var/www/html/*.php /var/www/html/
COPY --from=mediawiki /var/www/html/resources  /var/www/html/resources
COPY --from=mediawiki /var/www/html/skins      /var/www/html/skins
COPY --from=mediawiki /var/www/html/extensions /var/www/html/extensions

COPY config/robots.txt /var/www/html/robots.txt
COPY config/default.conf /etc/nginx/conf.d/default.conf
