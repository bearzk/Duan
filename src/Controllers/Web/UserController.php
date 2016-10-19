<?php
namespace Duan\Controllers\Web;

use Duan\DuanApp;
use Duan\Models\User;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    public function show(DuanApp $app, Request $request, $id)
    {
        /** @var \Twig_Environment $view */
        $view = $app['twig'];
        /** @var User $user */
        $user = User::find($id);

        /** @var Token $sessionToken */
        $sessionToken = $app->getSessionToken();
        $sessionEmail = $sessionToken->getClaim('email');

        if ($user->email == $sessionEmail) {
            return new RedirectResponse('/signin');
        }

        return $view->render('pages/user.twig', ['user' => $user]);
    }
}
