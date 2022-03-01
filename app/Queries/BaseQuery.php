<?php

namespace App\Queries;

use App\ApiBuilder\Builder\EpidemicAPI;
use App\DTO\V1\Epidemic\CaseList;
use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use RuntimeException;

class BaseQuery
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Call National Epidemic API
     *
     * @param string $path
     * @param string $object
     * @return Collection
     */
    protected function callNationalEpidemicAPI(string $path, string $object): Collection
    {
        if (!$path)
            throw new RuntimeException('No path is configured.');

        if (!$object)
            throw new RuntimeException('No DTO (Data Transfer Object) is configured.');

        $result = EpidemicAPI::to('GET', $path)->send();
        $result = csvConvert($result->response);

        $data = $columnNames = [];
        $epidemicData = collect();

        foreach ($result[0] as $name)
            $columnNames[] = $name;

        foreach ($result as $key => $value) {
            if ($key === 0 || $key === array_key_last($result)) continue;
            foreach ($columnNames as $columnKey => $columnName)
                $data[$key-1][$columnName] = $value[$columnKey];
            $epidemicData->push(new $object($data[$key-1]));
        }

        return $epidemicData;
    }

    protected function callStateEpidemicAPI(string $path, string $object): Collection
    {
        if (!$path)
            throw new RuntimeException('No path is configured.');

        if (!$object)
            throw new RuntimeException('No DTO (Data Transfer Object) is configured.');

        $result = EpidemicAPI::to('GET', $path)->send();
        $result = csvConvert($result->response);

        $states = array_keys(Config::get('setting.population'));
        array_shift($states);

        $epidemicStateData = collect();

        foreach ($result as $key => $value) {
            if ($key === 0 || $key === array_key_last($result)) continue;
            if ($key % 16 === 1)
                $currentDateData[$states[$key % 16 - 1]] = new CaseList([
                    'cases_import' => $value[2],
                    'cases_new' => $value[3],
                    'cases_recovered' => $value[4]
                ]);
            elseif ($key % 16 === 0) {
                $currentDateData[$states[15]] = new CaseList([
                    'cases_import' => $value[2],
                    'cases_new' => $value[3],
                    'cases_recovered' => $value[4]
                ]);
                $dataItem = ['date' => $value[0], 'states' => $currentDateData];
                $epidemicStateData->push(new $object($dataItem));
            }
            else
                $currentDateData[$states[$key % 16 - 1]] = new CaseList([
                    'cases_import' => $value[2],
                    'cases_new' => $value[3],
                    'cases_recovered' => $value[4]
                ]);
        }

        return $epidemicStateData;
    }

    /**
     * Query Collection
     *
     * @param Collection $collection
     * @param array|null $request
     * @return Collection
     */
    protected function query(Collection $collection, array $request = null)
    {
        if (isset($request['sort'])) {
            if (isset($request['order']) && $request['order'] == 'desc')
                $collection = $collection->sortByDesc($request['sort']);
            else
                $collection = $collection->sortBy($request['sort']);
        }

        if (isset($request['state'])) {
            if (!in_array($request['state'], array_keys(Config::get('setting.population'))))
                throw new RuntimeException('State given is invalid.');

            $collection = $collection->map(function ($epidemic) use ($request) {
                $epidemic->states = [$request['state'] => $epidemic->states[$request['state']]];
                return $epidemic;
            });
        }

        if (isset($request['start_date']))
            $collection = $collection->filter(function ($epidemic) use ($request) {
                return Carbon::parse($request['start_date'])->lte(Carbon::parse($epidemic->date));
            });

        if (isset($request['end_date']))
            $collection = $collection->filter(function ($epidemic) use ($request) {
                return Carbon::parse($request['end_date'])->gte(Carbon::parse($epidemic->date));
            });

        if (isset($request['month']))
            $collection = $collection->filter(function ($epidemic) use ($request) {
                return Carbon::parse($epidemic->date)->month == $request['month'];
            });

        if (isset($request['year']))
            $collection = $collection->filter(function ($epidemic) use ($request) {
                return Carbon::parse($epidemic->date)->year == $request['year'];
            });

        return $collection;
    }
}
