<?php

use Cicada\Routing\RouteCollection;
use Duan\Controllers\Api\UrlController;
use Duan\Controllers\Web\AuthController;
use Duan\Controllers\Web\IndexController;
use Duan\Controllers\Web\UserController;

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

$api->before($checkApiContentType);
$api->before($tokenAuth);

$app->addRouteCollection($api);

/* ------------------------------------------------------------------
 * Web Controllers
 */

// basic

/** @var RouteCollection $web */
$web = $app['collection_factory'];

$web->prefix('/');

$web->get('', [IndexController::class, "index"])
    ->name('duan::create');

$web->post('', [IndexController::class, "save"])
    ->name('duan::save');

// authentication

$web->get('signin', [AuthController::class, "signinForm"])
    ->name('auth::signin_form');

$web->post('signin', [AuthController::class, "signin"])
    ->name('auth::signin');

$web->get('signup', [AuthController::class, 'signupForm'])
    ->name('auth::signup_form');

$web->post('signup', [AuthController::class, "signup"])
    ->name('auth::signup');

$web->post('signout', [AuthController::class, "signOut"])
    ->name('auth::signout');

// user

$web->get('user/{id}', [UserController::class, "show"])
    ->name('user::show');

$web->get('{hash}', [IndexController::class, "redirect"])
    ->name('duan::redirect');

$web->before($CSRFVerifyAndGenerate);

$app->addRouteCollection($web);
