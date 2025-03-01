<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
enum Page: string
{
    case HOME = 'home_page';
    case KEY_DOCUMENT = 'key_document';

    case CONTACT = 'contact';

    case PRESIDING_COUNCIL = 'PRESIDING_COUNCIL';

    case MEMBERSHIP = 'membership';
}
