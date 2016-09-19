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
        /** @var TokenService $csrf */
        $csrf = $app['csrf'];
        $token = $csrf->generate();

        $context = ['token' => $token];

        $twig = $app['twig'];

        return $twig->render('pages/create.twig', ['context' => $context]);
    }

    public function save(DuanApp $app, Request $request)
    {
        $u = $request->get('url');
        $h = $request->get('hash');
        $t = $request->get('token');

        /** @var TokenService $csrf */
        $twig = $app['twig'];

        $csrf = $app['csrf'];
        $token = $csrf->generate();
        $context['token'] = $token;

        if (!$csrf->validate($t)) {
            $context['hash'] = 'invalid token';
            return $twig->render('pages/create.twig', ['context' => $context]);
        }

        $this->checkToBack($u);

        if (!empty($h)) {
            $url = Url::getByHash($h);
            if (empty($url)) {
                $url = new Url;
                $url->h = $h;
                $url->u = $u;
                $url->c = 1;
                $url->save();
            }
        } else {
            $url = Url::getByUrl($u);
            if (empty($url)) {
                $url = new Url;
                $url->u = $u;
                $url->h = Hash::gen($u);
                $url->save();
            }
        }

        $hash = $request->getScheme() . '://' . $request->getHost();
        if ('80' != $request->getPort()) {
            $hash .= ":" . $request->getPort();
        }
        $hash .= '/' . $url->h;

        $context['hash'] = $hash;

        return $twig->render('pages/create.twig', ['context' => $context]);
    }

    public function redirect(DuanApp $app, Request $request, $hash)
    {
        $url = Url::getByHash($hash);

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
}
