<?php

use Duan\Controllers\Api\UrlController;
use Duan\Controllers\Web\IndexController;

/* ------------------------------------------------------------------
 * Api Controllers
 */

$api = $app['collection_factory'];

$api->prefix('/api');

$api->get('/duan', [UrlController::class, "index"])
    ->name('api::duan_list');

$api->post('/duan', [UrlController::class, "save"])
    ->name('api::duan_save');

$api->get('/duan/{hash}', [UrlController::class, "get"])
    ->name('api::duan_get_item');

$app->addRouteCollection($api);

/* ------------------------------------------------------------------
 * Web Controllers
 */

$app->get('/', [IndexController::class, "index"])
    ->name('duan::create');

$app->post('/', [IndexController::class, "save"])
    ->name('duan::save');

$app->get('/{hash}', [IndexController::class, "redirect"])
    ->name('duan::redirect');


