<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Log extends Model
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
        'endpoint',
        'request',
        'response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'request'  => 'array',
        'response' => 'array',
    ];

    /** @var string */
    const CACHE_TAG = 'log_query';

    /** @var string */
    public $rememberCacheTag = 'log_query';
}
