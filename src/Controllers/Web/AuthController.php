<?php
namespace Duan\Controllers\Web;

use Duan\DuanApp;
use Duan\Exceptions\InvalidArgumentException;
use Duan\Lib\Authenticator;
use Duan\Lib\JWTFacade;
use Duan\Lib\WebAuthenticator;
use Duan\Models\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    public function signupForm(DuanApp $app, Request $request)
    {
        /** @var \Twig_Environment $view */
        $view = $app['twig'];
        return $view->render('pages/signup.twig');
    }

    public function signup(DuanApp $app, Request $request)
    {
        $view = $app['twig'];
        $email = $request->get('email');
        $password = $request->get('password');
        $alias = $request->get('alias');
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');

        try {
            User::create($app['jwt'], $email, $password, $alias, $firstName, $lastName, true, 'personal');
        } catch (InvalidArgumentException $ex) {
            return $view->render('pages/signup.twig');
        }

        redirect('/signin');
    }

    public function signinForm(DuanApp $app, Request $request)
    {
        /** @var \Twig_Environment $view */
        $view = $app['twig'];
        return $view->render('pages/login.twig');
    }

    public function signin(DuanApp $app, Request $request)
    {
        /** @var WebAuthenticator $auth */
        $auth = $app['auth'];
        $user = $auth->auth($request);
        if ($user) {
            // TODO: generate jwt and put in header
            // /user/user->id should be protected by jwt auth
            /** @var JWTFacade $jwt */
            $jwt = $app['jwt'];

            $expiresIn = 14;
            $token = (string) $jwt->build(['email' => $user->email, 'login' => true], $expiresIn);
            $res = new RedirectResponse("/user/$user->id");
            $res->headers->setCookie(new Cookie('session', $token, time() + 3600 * 24 * $expiresIn));
            return $res;
        }
        redirect('/signin');
    }

    public function signout(DuanApp $app, Request $request)
    {

    }
}
