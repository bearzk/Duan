<?php
namespace Duan\Controllers;

use Duan\DuanApp;
use Duan\Lib\Hash;
use Duan\Lib\UrlValidator;
use Duan\Models\Url;
use Schnittstabil\Csrf\TokenService\TokenService;
use Symfony\Component\HttpFoundation\Request;

class ShortController
{
    public function create(DuanApp $app, Request $request)
    {
        $context = [];

        $this->addToken($app, $context);

        $twig = $app['twig'];

        return $twig->render('pages/create.twig', ['context' => $context]);
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

    public function redirect(DuanApp $app, Request $request, $hash)
    {
        /** @var Url $url */
        $url = Url::find($hash);

        $this->checkToBack($url->u);

        redirect($url->u);
    }

    private function checkToBack($url)
    {
        if (empty($url) or !UrlValidator::validate($url)) {
            header('Location: /');
            exit(0);
        }
    }

    private function addToken(DuanApp $app, Array &$context)
    {
        /** @var TokenService $csrf */
        $csrf = $app['csrf'];
        $token = $csrf->generate();

        $context['token'] = $token;
    }

    private function saveUrl($h, $u)
    {
        if (empty($h)) {
            $url = Url::getByUrl($u);
        } else {
            $url = Url::find($h);
        }

        if (empty($url)) {
            $url = new Url;
            $url->u = $u;
            if (empty($h)) {
                $url->h = Hash::gen($u);
            } else {
                $url->h = $h;
                $url->c = 1;
            }
            $url->save();
        }

        return $url;
    }

    private function buildResultUrl(Request $request, Url $url)
    {
        $result = $request->getScheme() . '://' . $request->getHost();
        if ('80' != $request->getPort()) {
            $result .= ":" . $request->getPort();
        }
        $result .= '/' . $url->h;

        return $result;
    }
}
