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

// basic

$app->get('/', [IndexController::class, "index"])
    ->name('duan::create');

$app->post('/', [IndexController::class, "save"])
    ->name('duan::save');

$app->get('/{hash}', [IndexController::class, "redirect"])
    ->name('duan::redirect');

// authentication

$app->get('/signin', [AuthController::class, "signinForm"])
    ->name('auth::signin_form');

$app->post('/login', [AuthController::class, "signin"])
    ->name('auth::signin');

$app->get('/signup', [AuthController::class, 'signupForm'])
    ->name('auth::signup_form');

$app->post('/signup', [AuthController::class, "signup"])
    ->name('auth::signup');

$app->post('/signout', [AuthController::class, "signOut"])
    ->name('auth::signout');

// user

$app->get('/user/{id}', [UserController::class, "show"])
    ->name('user::show');
