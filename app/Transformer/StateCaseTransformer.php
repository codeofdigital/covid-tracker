<?php

namespace App\Transformer;

use App\DTO\V1\Epidemic\StateCaseObject;

class StateCaseTransformer extends BaseTransformer
{
    public function transform(StateCaseObject $object): array
    {
        return toArray($object);
    }

    public function getResourceKey(): string
    {
        return 'state_case';
    }
}
