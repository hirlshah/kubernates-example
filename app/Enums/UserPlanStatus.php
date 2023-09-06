<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ACTIVE()
 * @method static static EXPIRED()
 */
final class UserPlanStatus extends Enum
{
    const ACTIVE = 'active';
    const EXPIRED = 'expired';
}
