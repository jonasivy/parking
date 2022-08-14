<?php

namespace App\Models;

use App\Models\Slot\Type;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Slot extends Model
{
    use HasFactory,
        Rememberable,
        Cacheable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slot_type_id',
        'x_axis',
        'y_axis',
    ];

    /** @var string */
    const CACHE_TAG = 'slot_query';

    /** @var string */
    public $rememberCacheTag = 'slot_query';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'slot_type_id');
    }
}
