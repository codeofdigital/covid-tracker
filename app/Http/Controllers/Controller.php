<?php

namespace App\Http\Controllers;

use App\Transformer\BaseTransformer;
use BadMethodCallException;
use Closure;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use Helpers;

    protected function paginatorOrCollection($paginatorOrCollection, $transformer, array $parameters = [], Closure $after = null): Response
    {
        $method = null;

        if ($paginatorOrCollection instanceof Paginator)
            $method = 'paginator';
        elseif ($paginatorOrCollection instanceof Collection)
            $method = 'collection';

        if (!$method)
            throw new BadMethodCallException('No method was applied.');

        $parameters = $this->addResourceKey($transformer, $parameters);

        $response = $this->{$method}(
            $paginatorOrCollection,
            $transformer,
            $parameters,
            $after
        );

        return $this->addAvailableIncludes($response, $transformer);
    }

    private function addResourceKey($transformer, $parameters): array
    {
        $parameters += ['key' => $this->checkTransformer($transformer)->getResourceKey()];
        return $parameters;
    }

    private function checkTransformer($transformer): BaseTransformer
    {
        if (is_string($transformer))
            $transformer = app($transformer);

        return $transformer;
    }

    private function addAvailableIncludes(Response $response, $transformer): Response
    {
        return $response->addMeta('include', $this->checkTransformer($transformer)->getAvailableIncludes());
    }
}
