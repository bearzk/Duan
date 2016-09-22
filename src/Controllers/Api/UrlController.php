<?php
namespace Duan\Controllers\Api;

use Duan\DuanApp;
use Duan\Models\Url;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

        return new JsonResponse($url);
    }

    public function get(DuanApp $app, Request $request, $hash)
    {
        $url = Url::find($hash);
        return new JsonResponse($url);
    }
}
