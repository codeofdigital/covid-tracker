<?php

namespace App\DTO\V1\Epidemic;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class DeathNationalObject extends FlexibleDataTransferObject
{
    /** @var string|null */
    public $date;

    /** @var string|int|null */
    public $deaths_new;

    /** @var string|int|null */
    public $deaths_new_dod;

    /** @var string|int|null */
    public $deaths_bid;

    /** @var string|int|null */
    public $deaths_bid_dod;
}
