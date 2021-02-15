<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bash
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Bash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bash newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bash query()
 * @mixin \Eloquent
 */
class Bash extends Model
{
    const BASH_COMMAND_TO_FIND = 'grep -rP "server_name\s*{domain_name}" '
                                    .NginxServer::PLACEHOLDER_CONFIG_PATH
                                    .' | cut -d " " -f1 | tr -d ":" | head -1';
}
