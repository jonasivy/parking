<?php

namespace App\Models\Slot;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Type extends Model
{
    use HasFactory,
        Rememberable,
        Cacheable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'slot_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
    ];

    /** @var string */
    const CACHE_TAG = 'slot_type_query';

    /** @var string */
    public $rememberCacheTag = 'slot_type_query';
}
