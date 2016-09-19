<?php

use Duan\Controllers\ShortController;

$app->get('/', [ShortController::class, "create"])
    ->name('duan::create');

$app->post('/', [ShortController::class, "save"])
    ->name('duan::save');

$app->get('/{hash}', [ShortController::class, "redirect"])
    ->name('duan::redirect');

