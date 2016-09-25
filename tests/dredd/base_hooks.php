<?php

require './vendor/autoload.php';

use Dredd\Hooks;
use Duan\DuanApp;
use Duan\Models\Url;
use Phormium\DB;

$params = [
    [
        'url' => 'http://www.example.com/',
        'hash' => 'example'
    ],
    [
        'url' => 'https://twitter.com/',
        'hash' => 'ttt'
    ]
];


Hooks::beforeAll(function (&$transaction) use ($params) {

    $env = 'test';

    $app = new DuanApp($env);

    $app->configure();

    DB::begin();

    array_map(function($param) {
        Url::create($param);
    }, $params);

});

Hooks::afterAll(function (&$transaction) {
    DB::rollback();
});
