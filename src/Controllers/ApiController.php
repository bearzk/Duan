<?php
namespace Duan\Controllers;

use Duan\DuanApp;
use Duan\Lib\Hash;
use Duan\Lib\UrlValidator;
use Duan\Models\Url;
use Schnittstabil\Csrf\TokenService\TokenService;
use Symfony\Component\HttpFoundation\Request;

class ApiController
{
    public function index(DuanApp $app, Request $request)
    {

    }

    public function save(DuanApp $app, Request $request)
    {
        $u = $request->get('url');
        $h = $request->get('hash');
        $t = $request->get('token');

        $context = [];
        $twig = $app['twig'];

        $this->addToken($app, $context);

        if (!$app['csrf']->validate($t)) {
            $context['result'] = 'invalid token';
            return $twig->render('pages/create.twig', ['context' => $context]);
        }

        $this->checkToBack($u);

        $url = $this->saveUrl($h, $u);

        $context['result'] = $this->buildResultUrl($request, $url);

        return $twig->render('pages/create.twig', ['context' => $context]);
    }

    public function get(DuanApp $app, Request $request, $hash)
    {

    }
}
