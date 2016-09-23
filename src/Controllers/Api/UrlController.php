<?php
namespace Duan\Controllers\Api;

use Cicada\Routing\Router;
use Duan\DuanApp;
use Duan\Models\Url;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UrlController
{
    public function index(DuanApp $app, Request $request)
    {
        /** @var Url $urls */
        $urls = Url::all();
        return new JsonResponse($urls);
    }

    public function save(DuanApp $app, Request $request)
    {
        $params = (array) json_decode($request->getContent());
        $url = Url::create($params);

        /** @var Router $router */
        $router = $app['router'];

        $resultUrl = $router->getRoutePath('api::duan_get_item', ['hash' => $url->hash]);

        return new JsonResponse($url, Response::HTTP_CREATED, ['Location' => $resultUrl]);
    }

    public function get(DuanApp $app, Request $request, $hash)
    {
        $url = Url::find($hash);
        return new JsonResponse($url);
    }
}
