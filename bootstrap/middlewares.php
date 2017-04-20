<?php

use Duan\DuanApp;
use Duan\Lib\TokenAuthenticator;
use Schnittstabil\Csrf\TokenService\TokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
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
 * Api
 */

// Content-Type: application/json

$checkApiContentType = function (DuanApp $app, Request $request) {
    if ('json' != $request->getContentType()) {
        return new JsonResponse(
            ["message" => "Unsupported Media Type"],
            Response::HTTP_UNSUPPORTED_MEDIA_TYPE
        );
    }
};


// Token Auth

$apiTokenAuth = function (DuanApp $app, Request $request) {
    /** @var TokenAuthenticator $tokenAuth*/
    $tokenAuth = $app['token_auth'];
    if (!$tokenAuth->auth($request)) {
        return new JsonResponse (
            ["message" => "Forbidden"],
            Response::HTTP_FORBIDDEN
        );
    }
};

/* ------------------------------------------------------------------
 * CSRF token
 */

$CSRFVerify = function (DuanApp $app, Request $request) {
    /** @var TokenService $csrf */
    $csrf = $app['csrf'];
    /** @var Twig_Environment $twig */
    $twig = $app['twig'];

    if ('POST' == $request->getMethod()) {
        if (!$csrf->validate($request->get('csrf_token'))) {
            $error = [
                'message' => 'invalid csrf token.',
                'code' => Response::HTTP_BAD_REQUEST,
            ];
            return new Response(
                $twig->render(
                    'pages/503.twig',
                    ['error' => $error]
                ),
                Response::HTTP_BAD_REQUEST
            );
        }
    }
};

/* ------------------------------------------------------------------
 * Web Cookie Auth
 */

$webCookieAuthBefore = function (DuanApp $app, Request $request) {
    /** @var \Duan\Lib\WebAuthenticator $authenticator */
    $authenticator = $app['auth'];
    return $authenticator->cookieAuth($request);
};

$webCookieAuthAfter = function (DuanApp $app, Request $request, Response $response) {
    /** @var \Duan\Lib\WebAuthenticator $authenticator */
    $authenticator = $app['auth'];
    $authenticator->refreshToken($response);
};
