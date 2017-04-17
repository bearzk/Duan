<?php

use Cicada\Routing\RouteCollection;
use Duan\Controllers\Web\AuthController;
use Duan\Controllers\Web\IndexController;
use Duan\Controllers\Web;

/* ------------------------------------------------------------------
 * Web Controllers
 */

// restricted

/** @var RouteCollection $restricted */
$restricted = $app['collection_factory'];

$restricted->prefix('/');

$restricted->get('user', [Web\UserController::class, "show"])
    ->name('user::show');

$restricted->get('signout', [AuthController::class, "signout"])
    ->name('auth::signout');

$restricted->before($CSRFVerify);
$restricted->before($webCookieAuthBefore);
$restricted->after($webCookieAuthAfter);

$app->addRouteCollection($restricted);


// open

/** @var RouteCollection $open */
$open = $app['collection_factory'];

$open->prefix('/');

    // authentication

    $open->get('signin', [AuthController::class, "signinForm"])
        ->name('auth::signin_form');

    $open->post('signin', [AuthController::class, "signin"])
        ->name('auth::signin');

    $open->get('signup', [AuthController::class, 'signupForm'])
        ->name('auth::signup_form');

    $open->post('signup', [AuthController::class, "signup"])
        ->name('auth::signup');

    // basic
    $open->get('', [IndexController::class, "index"])
        ->name('duan::create');

    $open->post('', [IndexController::class, "save"])
        ->name('duan::save');

    $open->get('{hash}', [IndexController::class, "redirect"])
        ->assert('hash', '[a-zA-Z0-9]+')
        ->name('duan::redirect');

$open->before($CSRFVerify);

$app->addRouteCollection($open);

