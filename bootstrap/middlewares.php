<?php

use Duan\DuanApp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/* ------------------------------------------------------------------
 * Logs
 */

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

/* ------------------------------------------------------------------
 * CSRF token
 */

$checkApiContentType = function (DuanApp $app, Request $request) {
    // check if content-type is application/Json
    // reject when not with "415 Unsupported Media Type"
}

/* ------------------------------------------------------------------
 * CSRF token
 */

$CSRFVerify = function (DuanApp $app, Request $request) {
    if ('POST' == $request->getMethod()) {
        // verify csrf token
    }
}

$CSRFgenerate = function(DuanApp $app, Request $request) {
    /** @var TokenService $csrf */
    $csrf = $app['csrf'];
    /** @var Twig_Environment $csrf */
    $twig = $app['twig'];
}
