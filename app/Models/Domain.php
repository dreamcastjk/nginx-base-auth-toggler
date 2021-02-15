<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Domain
 *
 * @property int $id
 * @property string $domain
 * @property string $file_path
 * @property string|null $old
 * @property string|null $new
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|Domain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Domain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Domain query()
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Domain whereStatus($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User $user
 */
class Domain extends Model
{
    const REGEX_DOMAIN = '^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$';

    const PLACEHOLDER_DOMAIN = '{domain_name}';

    const TABLE_NAME = 'domains';

    const COLUMN_USER_ID = 'user_id';

    const COLUMN_STATUS = 'status';

    const COLUMN_DOMAIN = 'domain';

    const STATUS_ENABLED = 1;

    const STATUS_DISABLED = 2;

    const PAGINATION_DEFAULT = 10;

    protected $guarded = ['id'];

    protected $perPage = self::PAGINATION_DEFAULT;

    /**
     * User that toggles base auth on domain.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
