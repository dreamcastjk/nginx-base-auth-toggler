<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NginxServer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|NginxServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NginxServer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NginxServer query()
 * @mixin \Eloquent
 */
class NginxServer extends Model
{
    const CODE_SUCCESS_EXEC_CHECK = 0;

    const BASH_COMMAND_RELOAD_NGINX = 'sudo systemctl reload nginx';

    const AUTH_BASIC_RESTRICTED_STRING = 'auth_basic "Restricted";';

    const AUTH_BASIC_USER_FILE_STRING = 'auth_basic_user_file';

    const AUTH_BASIC_RESTRICTED_STRING_COMMENTED = '#auth_basic "Restricted";';

    const AUTH_BASIC_USER_FILE_STRING_COMMENTED = '#auth_basic_user_file';

    const SYMBOL_TO_REPLACE = '#';

    const BASH_COMMAND_CHECK_NGINX_CONFIGS = 'sudo nginx -t 2>&1';

    const PLACEHOLDER_CONFIG_PATH = '{config_path}';
}
