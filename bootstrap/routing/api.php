<?php
use Cicada\Routing\RouteCollection;
use Duan\Controllers\Api\UrlController;
use Duan\Controllers\Api;

/* ------------------------------------------------------------------
 * Api Controllers
 */

/** @var RouteCollection $api */
$api = $app['collection_factory'];

$api->prefix('/api/');

$api->get('duan', [UrlController::class, "index"])
    ->name('api::duan_list');

$api->post('duan', [UrlController::class, "save"])
    ->name('api::duan_save');

$api->get('duan/{hash}', [UrlController::class, "get"])
    ->name('api::duan_get_item');

$api->get('user', [Api\UserController::class, 'get'])
    ->name('api::user');

$api->get('user/tokens', [Api\UserController::class, 'tokens'])
    ->name('api::user_tokens');

$api->post('user/tokens', [Api\UserController::class, 'newToken'])
    ->name('api::user_new_token');

$api->before($checkApiContentType);
$api->before($apiTokenAuth);

$app->addRouteCollection($api);
