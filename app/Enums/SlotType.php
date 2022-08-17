<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SlotType extends Enum
{
    const INITIAL = 40;
    const SMALL = 20;
    const MEDIUM = 60;
    const LARGE = 100;
    const DAY = 5000;
}
