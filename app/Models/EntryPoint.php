<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class EntryPoint extends Model
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
        'x_axis',
        'y_axis',
    ];

    /** @var string */
    const CACHE_TAG = 'entry_point_query';

    /** @var string */
    public $rememberCacheTag = 'entry_point_query';
}
