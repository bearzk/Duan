<?php
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

    }

    public function signin(DuanApp $app, Request $request)
    {
        $app['auth']->auth($request);

    }

    public function signout(DuanApp $app, Request $request)
    {

    }
}
