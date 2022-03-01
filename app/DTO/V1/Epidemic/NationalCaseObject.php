<?php

namespace App\DTO\V1\Epidemic;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class NationalCaseObject extends FlexibleDataTransferObject
{
    /** @var string|null */
    public $date;

    /** @var string|int|null */
    public $cases_new;

    /** @var string|int|null */
    public $cases_import;

    /** @var string|int|null */
    public $cases_recovered;

    /** @var string|int|null */
    public $cluster_import;

    /** @var string|int|null */
    public $cluster_religious;

    /** @var string|int|null */
    public $cluster_highRisk;

    /** @var string|int|null */
    public $cluster_education;

    /** @var string|int|null */
    public $cluster_detentionCentre;

    /** @var string|int|null */
    public $cluster_workplace;
}
