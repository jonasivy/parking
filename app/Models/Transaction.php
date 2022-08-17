<?php

namespace App\Models;

use App\Enums\TransactionType;
use App\Models\Slot\Type as SlotType;
use App\Models\Vehicle\Type as VehicleType;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Rememberable\Rememberable;

class Transaction extends Model
{
    use HasFactory,
        Rememberable,
        Cacheable,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'txn_id',
        'txn_ref_id',
        'entry_point_id',
        'slot_id',
        'slot_type_id',
        'vehicle_type_id',
        'plate_no',
        'initial_parking_fee',
        'succeeding_parking_fee',
        'day_fee',
        'parked_log_id',
        'parked_at',
        'unparked_log_id',
        'unparked_at',
        'status_flag',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'initial_parking_fee'    => 'float',
        'succeeding_parking_fee' => 'float',
        'day_fee'                => 'float',
    ];

    /** @var string */
    const CACHE_TAG = 'transaction_query';

    /** @var string */
    public $rememberCacheTag = 'transaction_query';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function enter()
    {
        return $this->belongsTo(Transaction::class, 'txn_ref_id', 'txn_ref_id')
            ->entered();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exit()
    {
        return $this->belongsTo(Transaction::class, 'txn_ref_id', 'txn_ref_id')
            ->exited();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|int $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEntered(Builder $query): Builder
    {
        return $query->where('type', TransactionType::fromKey('ENTER')->value);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|int $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExited(Builder $query): Builder
    {
        return $query->where('type', TransactionType::fromKey('EXIT')->value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entryPoint()
    {
        return $this->belongsTo(EntryPoint::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function slotType()
    {
        return $this->belongsTo(SlotType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parkLog()
    {
        return $this->belongsTo(Log::class, 'parked_log_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unparkLog()
    {
        return $this->belongsTo(Log::class, 'unparked_log_id');
    }
}
