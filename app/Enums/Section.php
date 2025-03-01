<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
enum Section: string
{
    case HOME_BANNER = 'home_banner';
    case HOME_ABOUT = 'home_about';
    case HOME_ABOUT_ITEM = 'home_about_item';
    case CORE_PUBLICATION = 'core_publications';
    case CORE_PUBLICATION_DOCUMENT = 'core_publication_document';
    case PRESIDING_COUNCIL = 'presiding_council';
    case HOW_TO_THE_GROUP = 'how_to_the_group';
    case HOW_TO_THE_GROUP_LIST = 'how_to_the_group_list';

    case HOW_HISTORY = 'home_history';
    case HOW_HISTORY_ITEM = 'home_history_item';

    case HOME_DONATION = 'home_donation';

    case KEY_DOCUMENT_BANNER = 'key_document_banner';
    case CONTACT_BANNER = 'contact_banner';
    case PRESIDING_COUNCIL_BANNER = 'PRESIDING_COUNCIL_banner';
    case PRESIDING_COUNCIL_ABOUT = 'PRESIDING_COUNCIL_ABOUT';

    case MEMBERSHIP_CONTENT = 'membership_content';

    case MEMBERSHIP_DEFAULT_ARTICLE = 'membership_default_article';
}
