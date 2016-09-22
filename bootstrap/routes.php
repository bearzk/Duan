<?php

use Duan\Controllers\ShortController;

/* ------------------------------------------------------------------
 * Web App
 */

$app->get('/', [WebController::class, "index"])
    ->name('duan::create');

//$app->post('/', [WebController::class, "save"])
//    ->name('duan::save');

$app->get('/{hash}', [WebController::class, "redirect"])
    ->name('duan::redirect');

/* ------------------------------------------------------------------
 * Api
 */

$app->get('/api/duan', [ApiController::class, "index"])
    ->name('api::duan_list');

$app->post('/api/duan', [ApiController::class, "save"])
    ->name('api::duan_save');

$app->get('/api/duan/{hash}', [ApiController::class, "get"])
    ->name('api::duan_get_item');
