<?php
namespace Duan\Controllers\Web;

use Duan\DuanApp;
use Duan\Lib\Hash;
use Duan\Lib\UrlValidator;
use Duan\Models\Url;
use Symfony\Component\HttpFoundation\Request;

class IndexController
{
    public function index(DuanApp $app, Request $request)
    {
        /** @var \Twig_Environment $view */
        $view = $app['twig'];

        $urls = Url::objects()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->fetch();

        return $view->render('pages/create.twig', compact('urls'));
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

        $urls = Url::objects()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->fetch();

        return $view->render('pages/create.twig', compact('urls', 'context'));
    }

    public function redirect(DuanApp $app, Request $request, $hash)
    {
        /** @var Url $url */
        $url = Url::find($hash);
        $url->clicks += 1;
        $url->update();

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
            $url->created_at = date('Y-m-d H:i:s');
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
