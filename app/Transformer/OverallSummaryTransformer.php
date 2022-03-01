<?php

namespace App\Transformer;

class OverallSummaryTransformer extends BaseTransformer
{
    public function transform($object): array
    {
        $activeCases = getSumFromArray($object->epidemic_data->sum('cases_new') - $object->epidemic_data->sum('cases_recovered') - $object->death_data->sum('deaths_new'));

        return [
            'total_population' => $object->total_population,
            'confirmed_cases' => $object->epidemic_data->sum('cases_new'),
            'local_cases' => $object->epidemic_data->sum('cases_new') - $object->epidemic_data->sum('cases_import'),
            'import_cases' => $object->epidemic_data->sum('cases_import'),
            'active_cases' => $activeCases,
            'active_case_percentage' => round(($activeCases / $object->epidemic_data->sum('cases_new')) * 100, 2),
            'recovered_cases' => $object->epidemic_data->sum('cases_recovered'),
            'recovered_case_percentage' => round(($object->epidemic_data->sum('cases_recovered') / $object->epidemic_data->sum('cases_new')) * 100, 2),
            'death_cases' => $object->death_data->sum('deaths_new'),
            'death_bid_cases' => $object->death_data->sum('deaths_bid'),
            'death_case_percentage' => round(($object->death_data->sum('deaths_new') / $object->epidemic_data->sum('cases_new')) * 100, 2)
        ];
    }

    public function getResourceKey(): string
    {
        return 'summary';
    }
}
