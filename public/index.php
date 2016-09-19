<?php

date_default_timezone_set('Europe/Berlin');
mb_internal_encoding("UTF-8");

require '../vendor/autoload.php';
require '../bootstrap/setup.php';
require '../bootstrap/routes.php';
require '../bootstrap/middlewares.php';
require '../bootstrap/exception-handling.php';
require '../bootstrap/functions.php';

$app->configure();
$app->boot();
$app->run();
