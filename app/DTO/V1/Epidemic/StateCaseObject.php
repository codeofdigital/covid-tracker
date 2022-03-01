<?php

namespace App\DTO\V1\Epidemic;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class StateCaseObject extends FlexibleDataTransferObject
{
    /** @var string|null */
    public $date;

    /** @var array|null */
    public $states;
}
