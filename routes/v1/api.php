<?php

$api->get(
    '/testing',
    function () {
        return [
            'message' => 'Hello World'
        ];
    }
);

$api->group(
    ['middleware' => 'api.throttle', 'limit' => config('setting.api.throttle.limit'), 'expires' => config('setting.api.throttle.expires')],
    function () use ($api) {
        $api->group(
            [
                'namespace' => 'Epidemic',
                'as' => 'epidemic'
            ],
            function () use ($api) {
                $api->group(
                    [
                        'prefix' => 'epidemic'
                    ],
                    function () use ($api) {
                        include 'epidemic/cases.php';
                    }
                );
            }
        );
    }
);
