<?php
namespace Duan\Controllers\Web;

use Duan\DuanApp;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    public function signupForm(DuanApp $app, Request $request)
    {

    }

    public function signup(DuanApp $app, Request $request)
    {

    }

    public function signinForm(DuanApp $app, Request $request)
    {
        /** @var \Twig_Environment $view */
        $view = $app['twig'];
        return $view->render('pages/login.twig');
    }

    public function signin(DuanApp $app, Request $request)
    {

    }

    public function signout(DuanApp $app, Request $request)
    {

    }
}
