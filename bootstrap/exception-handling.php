<?php
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

$app->exception(function(Exception $e) use ($app) {

    if (method_exists($e, 'getStatusCode')) {
        $status = $e->getStatusCode();
    } else {
        $status = $e->getCode();
    }

    switch ($status) {
        case Response::HTTP_NOT_FOUND:
            $template = '404.twig';
            $responseStatus = Response::HTTP_NOT_FOUND;
            break;

        case Response::HTTP_BAD_REQUEST:
            $responseStatus = Response::HTTP_BAD_REQUEST;
            $template = '503.twig';
            break;

        default:
            $template = '503.twig';
            $responseStatus = Response::HTTP_SERVICE_UNAVAILABLE;

            if (isset($app['raven'])) {
                $app['raven']->captureException($e);
            }
            break;
    }

    $context = [];
    if (method_exists($e, 'getContext')) {
        $context = (array)$e->getContext();
    }

    if (isset($app['twig'])) {
        $response = $app['twig']->render($template,[
            'context' => $context
        ]);
    } else {
        $response = 'An unexpected error has occurred.';
    }

    return new Response($response, $responseStatus);
});

$app['emitter']->on('router.nomatch', function() {
    throw new HttpException(404);
});

