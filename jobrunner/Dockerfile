FROM mediawiki

ARG JOBRUNNER_REF=6c10e770bd47f452aec22edba6fc8e7bd9d7a8ea

RUN install-php-extensions pcntl sockets

WORKDIR /var/www/html

RUN set -eux; \
    git clone https://github.com/weirdgloop/mediawiki-services-jobrunner.git; \
    cd mediawiki-services-jobrunner; \
    git checkout "$JOBRUNNER_REF"; \
    composer config --no-plugins --unset require-dev; \
    composer config --no-plugins allow-plugins.composer/installers true; \
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --ignore-platform-reqs --no-interaction --no-scripts; \
    rm -rf .git

COPY jobrunner-conf.json entrypoint.sh /var/www/html/mediawiki-services-jobrunner/
RUN chmod 755 /var/www/html/mediawiki-services-jobrunner/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/mediawiki-services-jobrunner

USER www-data
ENTRYPOINT ["/var/www/html/mediawiki-services-jobrunner/entrypoint.sh"]
