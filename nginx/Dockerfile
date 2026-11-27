FROM mediawiki AS static
RUN set -eu; \
	cd /var/www/html; \
	for f in index.php api.php load.php rest.php thumb.php thumb_handler.php \
		img_auth.php opensearch_desc.php COPYING CREDITS; do \
		test -f "$f"; echo "$f"; \
	done > /tmp/files; \
	find extensions resources skins -type f \( \
		-iname '*.css' -o -iname '*.js' -o -iname '*.gif' -o -iname '*.jpg' \
		-o -iname '*.jpeg' -o -iname '*.png' -o -iname '*.svg' -o -iname '*.ico' \
		-o -iname '*.wasm' -o -iname '*.ttf' -o -iname '*.woff' -o -iname '*.woff2' \
	\) >> /tmp/files; \
	mkdir /tmp/static; \
	tar -cf - -T /tmp/files | tar -xf - -C /tmp/static

FROM nginx:1.31.6@sha256:60f0d4e986561e08804f75c211dcaf7fc5455699eada29f42cbe8a0a7253dd86
COPY --from=static --chown=www-data:www-data /tmp/static /var/www/html
COPY --chown=www-data:www-data config/robots.txt /var/www/html/robots.txt
COPY config/default.conf /etc/nginx/conf.d/default.conf
