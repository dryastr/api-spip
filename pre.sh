#!/bin/sh

# get env
echo "" > /app/.env
envfile=$(cat /app/.env 2> /dev/null)

if [[ $CLOUDSERVICE == "aws" ]]; then
        aws ssm get-parameter --with-decryption --name "ENV-M2-GLOBAL-DEV" --region "$ECSREGION" --query Parameter.Value --output text | tee -a /app/.env
        aws ssm get-parameter --with-decryption --name "$SSMNAME" --region "${ECSREGION}" --query Parameter.Value --output text | tee -a /app/.env
else
        while [[ -z $envfile ]]
        do
                aliyun kms GetSecretValue --SecretName $KMS_NAME | jq -r '.SecretData' > /app/.env
                envfile=$(cat /app/.env 2> /dev/null)
        done
fi

if [[ $CLOUDSERVICE == "aws" ]]; then
        cp supervisor-aws.ini /etc/supervisor.d/service.ini
else
        cp supervisor.dev /etc/supervisor.d/service.ini
fi

cat /app/deployment/opcache.ini >> /etc/php81/conf.d/00_opcache.ini
sed -i 's+\;\[inet_http_server\]+\[inet_http_server\]+' /etc/supervisord.conf
sed -i 's+;port=127.0.0.1:9001+port=0.0.0.0:9001+' /etc/supervisord.conf
sed -i -e "s/;newrelic.enabled = true/newrelic.enabled = true/" \
        -e "s/REPLACE_WITH_REAL_KEY/11427c28b6a8bde8ec0602cb3f0ef9fec229NRAL/" \
       -e "s/newrelic.appname[[:space:]]=[[:space:]].*/newrelic.appname=\"REPLACE_WITH_APP_NAME\"/" \
       -e '$anewrelic.distributed_tracing_enabled=true' \
       -e "s/;newrelic.span_events.max_samples_stored = 0/newrelic.span_events.max_samples_stored = 100/" \
       $(php -r "echo(PHP_CONFIG_FILE_SCAN_DIR);")/newrelic.ini

if [[ $APP_ENVIRONMENT == "dev" ]]; then
        #call artisan command
        sh artisan.sh
fi

composer dump-autoload
chmod -R 777 /app/storage
chown xfs.xfs storage/clockwork/index

