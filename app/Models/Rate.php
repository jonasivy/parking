<?php

namespace App\Models;

use App\Trait\Cacheable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Rate extends Model
{
    use HasFactory,
        Rememberable,
        Cacheable;
}
