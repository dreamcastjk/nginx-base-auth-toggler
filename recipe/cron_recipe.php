<?php

namespace Deployer;

task('cron:install', function () {
    run('job="* * * * * {{bin/php}} {{deploy_path}}/current/artisan schedule:run >> /dev/null 2>&1";'
        . 'ct=$(crontab -l |grep -i -v "$job");(echo "$ct" ;echo "$job") |crontab -');
})->desc('Adding the laravel cron to the user crontab');
