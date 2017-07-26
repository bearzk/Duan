<?php
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

$app->exception(function(Exception $e) use ($app) {
    $app['logger']->error($e);
    $app['logger']->error($e->getPrevious());

    if (method_exists($e, 'getStatusCode')) {
        $status = $e->getStatusCode();
    } else {
        $status = $e->getCode();
    }

    switch ($status) {
        case Response::HTTP_NOT_FOUND:
            $template = 'pages/404.twig';
            $responseStatus = Response::HTTP_NOT_FOUND;
            break;

        case Response::HTTP_BAD_REQUEST:
            $responseStatus = Response::HTTP_BAD_REQUEST;
            $template = 'pages/503.twig';
            break;

        default:
            $template = 'pages/503.twig';
            $responseStatus = Response::HTTP_SERVICE_UNAVAILABLE;

            if (isset($app['raven'])) {
                $app['raven']->captureException($e);
            }
            break;
    }

    $error = [];
    if (method_exists($e, 'getMessage')) {
        $error['message'] = $e->getMessage();
    }
    if (method_exists($e, 'getContext')) {
        $error['context'] = (array)$e->getContext();
    }
    if (method_exists($e, 'getTraceAsString')) {
        $error['trace_string'] = $e->getTraceAsString();
    }
    if (empty($e)) {
        $error = "An unexpected error occurred.";
    }

    if (isset($app['twig'])) {
        $response = $app['twig']->render($template, [
            'error' => $error
        ]);
    }

    return new Response($response);
});

$app['emitter']->on('router.nomatch', function() {
    throw new HttpException(404);
});

