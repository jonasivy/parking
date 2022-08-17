<?php

namespace App\Models;

use App\Models\Slot\Type;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Builder;
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|int $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType(Builder $query, $type): Builder
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOccupied(Builder $query): Builder
    {
        return $query->where('is_occupied', true);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVacant(Builder $query): Builder
    {
        return $query->where('is_occupied', false);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSmall(Builder $query): Builder
    {
        return $query->whereHas('type', function (Builder $query) {
            return $query->whereIn('name', [
                'Small',
                'Medium',
            ]);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMedium(Builder $query): Builder
    {
        return $query->whereHas('type', function (Builder $query) {
            return $query->whereIn('name', [
                'Medium',
                'Large',
            ]);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLarge(Builder $query): Builder
    {
        return $query->whereHas('type', function (Builder $query) {
            return $query->whereIn('name', [
                'Large',
            ]);
        });
    }
}
