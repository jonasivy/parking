<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ENTER()
 * @method static static EXIT()
 */
final class TransactionType extends Enum
{
    const ENTER = 1;
    const EXIT = 2;
}
