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
        'is_occupied',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_occupied' => 'boolean',
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

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string|int $type
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeType($query, $type)
    {
        return $query->whereHas('type', function ($query) use ($type) {
            if (is_numeric($type)) {
                return $query->where('id', $type);
            } else {
                return $query->where('code', $type);
            }
        });
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeOccupied($query)
    {
        return $query->where('is_occupied', true);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeVacant($query)
    {
        return $query->where('is_occupied', false);
    }
}
