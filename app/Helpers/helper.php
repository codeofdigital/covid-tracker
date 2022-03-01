<?php

use Illuminate\Support\Str;

if (!function_exists('toObject')) {
    function toObject(array $array): object
    {
        return json_decode(json_encode($array));
    }
}

if (!function_exists('toArray')) {
    function toArray($array): array
    {
        return json_decode(json_encode($array), true);
    }
}

if (!function_exists('isJson')) {
    function isJson($string, $associative = null): bool
    {
        if (!Str::startsWith($string, ['{', '['])) return false;

        $data = json_decode($string, $associative);

        if (!$associative && is_object($data) || $associative && is_array($data)) return true;

        return json_last_error() == JSON_ERROR_NONE;
    }
}

if (!function_exists('csvConvert')) {
    function csvConvert(string $data, bool $array = true) {
        $result = array_map('str_getcsv', explode("\n", $data));
        return $array ? $result : json_encode($result);
    }
}

if (!function_exists('getSumFromArray')) {
    function getSumFromArray(... $numbers) {
        $numbers = array_map(function ($number) {return $number === '' ? 0 : $number;}, $numbers);
        return array_sum($numbers);
    }
}
