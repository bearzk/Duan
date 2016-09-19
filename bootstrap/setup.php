<?php

use Duan\DuanApp;

$env = getenv('DUAN_ENV');

if (empty($env)) {
    header("HTTP/1.1 500 Internal Server Error");
    die("Environment variable DUAN_ENV not set. Cannot Continue.");
}

if (!in_array($env, DuanApp::$envs)) {
    header("HTTP/1.1 500 Internal Server Error");
    die("Invalid environment: \"$env\". Cannot continue.");
}

$app = new DuanApp($env);
