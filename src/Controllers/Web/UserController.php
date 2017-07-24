<?php
namespace Duan\Controllers\Web;

use Duan\DuanApp;
use Duan\Models\User;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    public function show(DuanApp $app, Request $request)
    {
        /** @var \Twig_Environment $view */
        $view = $app['twig'];
        /** @var User $user */
        $user = $app->getUser();
        $urls = $user->urls();
        return $view->render('pages/user.twig', compact('urls'));
    }
}
