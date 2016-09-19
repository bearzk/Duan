<?php


use Duan\DuanApp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$logRequest = function (DuanApp $app, Request $request) {
    $uri = $request->getUri();
    $method = $request->getMethod();

    $app['logger']->debug("Processing: $method $uri");
};

$logResponse = function (DuanApp $application, Response $response) {
    $code = $response->getStatusCode();
    $text = Response::$statusTexts[$code];

    $application['logger']->debug("Finished. Returned HTTP $code $text");
};

$app->before($logRequest);
$app->after($logResponse);
