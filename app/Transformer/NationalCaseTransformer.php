<?php

namespace App\Transformer;

use App\DTO\V1\Epidemic\NationalCaseObject;

class NationalCaseTransformer extends BaseTransformer
{
    public function transform(NationalCaseObject $object): array
    {
        return [
            'date' => $object->date,
            'new_cases' => intval($object->cases_new),
            'local_cases' => intval($object->cases_new - $object->cases_import),
            'import_cases' => intval($object->cases_import),
            'recovered_cases' => intval($object->cases_recovered),
            'sporadic_cases' => intval($object->cases_new - getSumFromArray($object->cluster_import, $object->cluster_religious, $object->cluster_highRisk, $object->cluster_education, $object->cluster_detentionCentre, $object->cluster_workplace)),
            'import_cluster' => empty($object->cluster_import) ? 0 : intval($object->cluster_import),
            'religious_cluster' => empty($object->cluster_religious) ? 0 : intval($object->cluster_religious),
            'high_risk_cluster' => empty($object->cluster_highRisk) ? 0 : intval($object->cluster_highRisk),
            'education_cluster' => empty($object->cluster_education) ? 0 : intval($object->cluster_education),
            'detention_cluster' => empty($object->cluster_detentionCentre) ? 0 : intval($object->cluster_detentionCentre),
            'workplace_cluster' => empty($object->cluster_workplace) ? 0 : intval($object->cluster_workplace),
        ];
    }

    public function getResourceKey(): string
    {
        return 'national_cases';
    }
}
