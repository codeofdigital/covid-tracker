<?php

$api->get('/summary', ['uses' => 'CaseController@overallSummary', 'as' => 'summary']);
$api->get('/national', ['uses' => 'CaseController@nationalCase', 'as' => 'national']);
$api->get('/state', ['uses' => 'CaseController@stateCase', 'as' => 'state']);
