<?php
namespace Duan\Controllers\Web;

use Duan\DuanApp;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    public function show(DuanApp $app, Request $request)
    {
        /** @var \Twig_Environment $view */
        $view = $app['twig'];
        return $view->render('pages/user.twig');
    }
}
