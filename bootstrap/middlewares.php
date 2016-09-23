<?php

use Duan\DuanApp;
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
 * Api Content-Type: application/json
 */

$checkApiContentType = function (DuanApp $app, Request $request) {
    $path = $request->getPathInfo();
    $pathArr = explode('/', $path);
    if ('json' != $request->getContentType() && 'api' == $pathArr[1]) {
        return new JsonResponse(
            ["message" => "Unsupported Media Type"],
            Response::HTTP_UNSUPPORTED_MEDIA_TYPE
        );
    }
};

$app->before($checkApiContentType);

/* ------------------------------------------------------------------
 * CSRF token
 */

$CSRFVerifyAndGenerate = function (DuanApp $app, Request $request) {
    $path = $request->getPathInfo();
    $pathArr = explode('/', $path);

    /** @var TokenService $csrf */
    $csrf = $app['csrf'];
    /** @var Twig_Environment $twig */
    $twig = $app['twig'];

    if ('api' != $pathArr[1]) {
        $twig->addGlobal('csrf_token', $csrf->generate());
    }
    if ('POST' == $request->getMethod() && 'api' != $pathArr[1]) {
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

$app->before($CSRFVerifyAndGenerate);
