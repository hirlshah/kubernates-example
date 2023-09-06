<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static IMAGE()
 * @method static static VIDEO()
 * @method static static PDF()
 */
final class AttachmentTypes extends Enum
{
    const IMAGE = 'image';
    const VIDEO =  'video';
    const PDF = 'pdf';
}
