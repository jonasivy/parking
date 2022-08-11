<?php

namespace App\Models\Vehicle;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Type extends Model
{
    use HasFactory,
        Rememberable,
        Cacheable;
}
