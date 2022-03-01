<?php

namespace App\Transformer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    abstract public function getResourceKey(): string;

    public function filterData(array $response, array $data, array $roleNames = null): array
    {
        if (app('auth')->user()->hasAnyRole(
            is_null($roleNames) ? config('setting.permission.role_names.system') : $roleNames
        )) {
            return array_merge($response, $data);
        }

        return $response;
    }

    public function addTimesHumanReadable(
        Model $entity,
        $responseData,
        array $columns = [],
        $isIncludeDefault = true
    ): array {
        $auth = app('auth');

        if (!$auth->check()) {
            return $responseData;
        }

        if (!$auth->user()->hasAnyRole(config('setting.permission.role_names'))) {
            return $responseData;
        }

        $timeZone = $auth->user()->timezone ?? config('app.timezone');

        $readable = function ($column) use ($entity, $timeZone) {
            // sometime column is not carbonated, i mean instance if Carbon/Carbon
            $at = Carbon::parse($entity->{$column});

            return [
                $column => $at->format(config('setting.formats.datetime_12')),
                $column.'_readable' => $at->diffForHumans(),
                $column.'_tz' => $at->timezone($timeZone)->format(config('setting.formats.datetime_12')),
                $column.'_readable_tz' => $at->timezone($timeZone)->diffForHumans(),
            ];
        };

        $isHasCustom = count($columns) > 0;

        $defaults = ['created_at', 'updated_at', 'deleted_at'];

        // only custom
        if ($isHasCustom && !$isIncludeDefault) {
            $toBeConvert = $columns;
        }  // custom and defaults
        elseif ($isHasCustom && $isIncludeDefault) {
            $toBeConvert = array_merge($columns, $defaults);
        } // only defaults
        else {
            $toBeConvert = $defaults;
        }

        $return = [];
        foreach ($toBeConvert as $column) {
            $return = array_merge(
                $return,
                (!is_null($entity->{$column})) ? array_merge($return, $readable($column)) : []
            );
        }

        return array_merge($responseData, $return);
    }

    protected function collection($data, $transformer, $resourceKey = null)
    {
        return parent::collection($data, $transformer, $resourceKey ?: $transformer->getResourceKey());
    }
}
