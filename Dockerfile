FROM composer AS vendor
WORKDIR /app
COPY composer.json ./
COPY composer.lock ./
COPY database database
#COPY app/Helpers app/Helpers
RUN composer install \
  --no-dev \
  --no-interaction \
  --prefer-dist \
  --ignore-platform-reqs \
  --optimize-autoloader \
  --apcu-autoloader \
  --ansi \
  --no-scripts

FROM REPLACE_WITH_REGISTRY_URL:octane-82-22
WORKDIR /app
COPY --from=vendor /app/vendor /app/vendor
COPY --chown=xfs:xfs . .
COPY --chown=xfs:xfs .env.example .env
RUN chmod -R 777 /app/storage
RUN chmod -R 777 /app/bootstrap/cache
CMD ["/run.sh"]
