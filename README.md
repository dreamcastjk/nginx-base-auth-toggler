<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Requirements

##### Back-end
```
php >= 7.4
mysql >= 5.7 or MariaDB >= 10.3
```

##### Front-end
```
yarn
```

# Local deploy for development
```shell script
composer install
cp .env.example .env # set your database connection
php artisan key:generate
php artisan migrate:fresh --seed
```

# Not standart .env parameters
Parameter                  | Description
---------------------------|---------------------------------------------------------------------------------------
MATTERMOST_HOOK_URL        | Your mattermost hook url. Example: https://YOUR_HOST/hooks/cdeko3ftgezasrtuzci6ds9bs
MATTERMOST_CHANNEL         | Channel name.
MATTERMOST_USERNAME        | Username who sends message.
MATTERMOST_ICON_URL        | Link to user icon image.
NGINX_CONFIGS_PATH         | Path to your nginx configs directory.
CRON_RESTORE_SCHEDULE_TIME | Time when need to restore all base auth on disabled sites. Default - 20:00.

# PHPDoc
```shell script
php artisan ide-helper:generate - PHPDoc generation for Laravel Facades
php artisan ide-helper:models - PHPDocs for models
php artisan ide-helper:meta - PhpStorm Meta file
```

# Important notes
```shell script
Intended only for nginx + phpfpm!
User must have SUDO permissions for executing "reload nginx" command in console.
Also need permissions on writing files for nginx virtual hosts.

Предназначена для работы в связке nginx + phpfpm!
Пользователь должен иметь права SUDO на исполнения reload nginx команд в консоли.
И быть права на запись файлов виртуальных хостов nginx.
```

#### Cron
To turn on base auth on schedule run:
```shell script
cron schedulers with commands will set authomaticaly after deploy. See laravel sheduler.
```

#### Server Deploy
```shell script
php artisan deploy 6rs [-o branch=brahchname]
```
# Deploy .env parameters
Parameter           | Description
--------------------|---------------------------------------------------------------------------------
DEPLOY_HOST         | Deploy host name. Example: stage1.ru
DEPLOY_PATH         | Deploy path on server. Example: /data/auth-toggler/auth_toggler
DEPLOY_USER         | User name with permissions.
DEPLOY_STAGE_NAME   | Deploy stage alias. Example: stage1
DEPLOY_SOCKET_PATH  | Socket path for cache reset. Example: /var/run/php/auth-toggler.stage1.ru.sock
