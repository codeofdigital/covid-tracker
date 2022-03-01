<?php

namespace App\DTO\V1\Epidemic;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class CaseList extends FlexibleDataTransferObject
{
    /** @var string|int|null */
    public $cases_import;

    /** @var string|int|null */
    public $cases_new;

    /** @var string|int|null */
    public $cases_recovered;
}
