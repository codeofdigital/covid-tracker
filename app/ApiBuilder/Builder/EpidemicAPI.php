<?php

namespace App\ApiBuilder\Builder;

use CodeOfDigital\ApiBuilder\ApiBuilder;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;

/**
 * @method static self to(string $method, string $path)
 * @method static self build(...$args)
 *
 * @see ApiBuilder
 */
class EpidemicAPI extends ApiBuilder
{
    protected $authorizationType = null;

    protected $baseUrl = 'https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic';
}
