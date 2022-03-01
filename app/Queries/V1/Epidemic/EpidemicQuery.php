<?php

namespace App\Queries\V1\Epidemic;

use App\DTO\V1\Epidemic\DeathNationalObject;
use App\DTO\V1\Epidemic\NationalCaseObject;
use App\DTO\V1\Epidemic\StateCaseObject;
use App\Queries\BaseQuery;
use Illuminate\Support\Facades\Config;

class EpidemicQuery extends BaseQuery
{
    public function getOverallSummary($request = null): array
    {
        $epidemicData = $this->callNationalEpidemicAPI(config('setting.path.national_case'), NationalCaseObject::class);
        $deathData = $this->callNationalEpidemicAPI(config('setting.path.death_national_case'), DeathNationalObject::class);
        $epidemicData = $this->query($epidemicData, $request);
        $deathData = $this->query($deathData, $request);

        return [
            'total_population' => Config::get('setting.population.malaysia.total_pop'),
            'epidemic_data' => $epidemicData,
            'death_data' => $deathData
        ];
    }

    public function getNationalCases($request = null)
    {
        $epidemicData = $this->callNationalEpidemicAPI(Config::get('setting.path.national_case'), NationalCaseObject::class);
        $epidemicData = $this->query($epidemicData, $request);
        return isset($request['paginate']) && $request['paginate'] ? $epidemicData->paginate() : $epidemicData;
    }

    public function getStateCases($request = null)
    {
        $stateEpidemicData = $this->callStateEpidemicAPI(Config::get('setting.path.state_case'), StateCaseObject::class);
        $stateEpidemicData = $this->query($stateEpidemicData, $request);
        return isset($request['paginate']) && $request['paginate'] ? $stateEpidemicData->paginate() : $stateEpidemicData;
    }
}
