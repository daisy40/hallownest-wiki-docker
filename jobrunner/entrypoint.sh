#!/bin/sh
DIR=/var/www/html/mediawiki-services-jobrunner

if [ "${RUNNER_TYPE:-job}" = "Chron" ]; then
    exec /usr/local/bin/php $DIR/redisJobChronService --config-file=$DIR/jobrunner-conf.json
else
    exec /usr/local/bin/php $DIR/redisJobRunnerService --config-file=$DIR/jobrunner-conf.json
fi
