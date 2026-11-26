FROM mediawiki:1.46-fpm

# System packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip curl ca-certificates libvips-tools \
    && rm -rf /var/lib/apt/lists/*

# Tools
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY --from=ghcr.io/mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions redis wikidiff2

# Cron
ARG SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.2.50/supercronic-linux-amd64
ARG SUPERCRONIC_SHA1=0795da10bf6303e9680a2c2bd8dd6b9aa33ebda2
RUN curl -fsSLo /usr/local/bin/supercronic "$SUPERCRONIC_URL" \
    && echo "$SUPERCRONIC_SHA1  /usr/local/bin/supercronic" | sha1sum -c - \
    && chmod +x /usr/local/bin/supercronic

# Elastica & CirrusSearch
ARG ELASTICA_REF=a3326af107aafd75f6958e8b4ae8e3d00f22b65f
ARG CIRRUS_REF=a89f321d857906a8450295aee40793d61ef18543
RUN set -eux; \
    for spec in "Elastica:$ELASTICA_REF" "CirrusSearch:$CIRRUS_REF"; do \
        name=${spec%%:*}; ref=${spec##*:}; \
        git init -q "extensions/$name"; \
        git -C "extensions/$name" fetch -q --depth 1 \
            "https://github.com/wikimedia/mediawiki-extensions-$name.git" "$ref"; \
        git -C "extensions/$name" checkout -q FETCH_HEAD; \
        rm -rf "extensions/$name/.git"; \
        COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --no-interaction \
            --optimize-autoloader -d "extensions/$name"; \
    done

# composer.local.json
COPY composer.local.json /var/www/html/composer.local.json
RUN COMPOSER_ALLOW_SUPERUSER=1 composer update --no-dev --no-interaction --optimize-autoloader

# PHP configs
COPY config/zz-custom.ini /usr/local/etc/php/conf.d/zz-custom.ini
COPY config/zz-pool.conf  /usr/local/etc/php-fpm.d/zz-pool.conf
