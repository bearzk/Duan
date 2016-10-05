<?php
namespace Duan\Controllers\Web;

use Duan\DuanApp;
use Duan\Lib\Hash;
use Duan\Lib\JWTFacade;
use Duan\Lib\UrlValidator;
use Duan\Models\Url;
use Symfony\Component\HttpFoundation\Request;

class IndexController
{
    public function index(DuanApp $app, Request $request)
    {
        $view = $app['twig'];

        $claims = [
            'name' => 'kai',
            'age' => 31
        ];

        /** @var JWTFacade $jwt */
        $jwt = $app['jwt'];
        $token = $jwt->build($claims);

        var_dump($jwt->validate($token));

        return $view->render('pages/create.twig');
    }

    public function save(DuanApp $app, Request $request)
    {
        $u = $request->get('url');
        $h = $request->get('hash');

        $context = [];
        $view = $app['twig'];

        $this->checkToBack($u);

        $url = $this->saveUrl($h, $u);

        $context['result'] = $this->buildResultUrl($request, $url);

        return $view->render('pages/create.twig', ['context' => $context]);
    }

    public function redirect(DuanApp $app, Request $request, $hash)
    {
        /** @var Url $url */
        $url = Url::find($hash);

        $this->checkToBack($url->url);

        redirect($url->url);
    }

    private function checkToBack($url)
    {
        if (empty($url) or !UrlValidator::validate($url)) {
            header('Location: /');
            exit(0);
        }
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
            $url->url = $u;
            if (empty($h)) {
                $url->hash = Hash::url($u);
            } else {
                $url->hash = $h;
                $url->customized = 1;
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
        $result .= '/' . $url->hash;

        return $result;
    }
}
