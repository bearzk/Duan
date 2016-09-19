<?php
namespace Duan\Controllers;

use Duan\DuanApp;
use Duan\Lib\Hash;
use Duan\Lib\UrlValidator;
use Duan\Models\Url;
use Symfony\Component\HttpFoundation\Request;

class ShortController
{
    public function create(DuanApp $app, Request $request)
    {
        $twig = $app['twig'];
        return $twig->render('create.twig');
    }

    public function save(DuanApp $app, Request $request)
    {
        $u = $request->get('url');
        $h = $request->get('hash');

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

        $twig = $app['twig'];

        $context['hash'] = $request->getScheme() . '://' . $request->getHost() . '/' . $url->h;

        return $twig->render('create.twig', ['context' => $context]);
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
