<?php
namespace Duan\Controllers\Web;

use Duan\DuanApp;
use Duan\Models\User;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    public function show(DuanApp $app, Request $request, $id)
    {
        /** @var \Twig_Environment $view */
        $view = $app['twig'];
        $user = User::find($id);
        return $view->render('pages/user.twig', ['user' => $user]);
    }
}
