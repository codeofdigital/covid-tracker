<?php

namespace App\Http\Controllers\V1\Epidemic;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Epidemic\NationalCaseRequest;
use App\Http\Requests\V1\Epidemic\StateCaseRequest;
use App\Queries\V1\Epidemic\EpidemicQuery;
use App\Transformer\NationalCaseTransformer;
use App\Transformer\OverallSummaryTransformer;
use App\Transformer\StateCaseTransformer;
use Dingo\Api\Http\Response;

class CaseController extends Controller
{
    protected $epidemicQuery;

    public function __construct(EpidemicQuery $epidemicQuery)
    {
        $this->epidemicQuery = $epidemicQuery;
    }

    public function overallSummary(NationalCaseRequest $request): Response
    {
        $validated = $request->validated();
        $summary = $this->epidemicQuery->getOverallSummary($validated);
        return $this->response->array($summary, OverallSummaryTransformer::class);
    }

    public function nationalCase(NationalCaseRequest $request): Response
    {
        $validated = $request->validated();
        $epidemicData = $this->epidemicQuery->getNationalCases($validated);
        return $this->paginatorOrCollection($epidemicData, NationalCaseTransformer::class);
    }

    public function stateCase(StateCaseRequest $request): Response
    {
        $validated = $request->validated();
        $stateEpidemicData = $this->epidemicQuery->getStateCases($validated);
        return $this->paginatorOrCollection($stateEpidemicData, StateCaseTransformer::class);
    }
}
