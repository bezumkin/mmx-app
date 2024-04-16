<?php

namespace MMX\App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property bool $active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Item extends Model
{
    protected $table = 'mmx_app_items';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'active' => 'bool',
    ];
}