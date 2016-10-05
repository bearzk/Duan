<?php
namespace Duan\Controllers\Api;

use Duan\DuanApp;
use Duan\Lib\JWTFacade;
use Duan\Models\User;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    protected $userFields = [
        'email',
    ];

    public function get(DuanApp $app, Request $request)
    {
        $tokenString = $request->cookies->get('duan_token');

        /** @var JWTFacade $jwt */
        $jwt = $app['jwt'];

        /** @var Token $token */
        $token = $jwt->parse($tokenString);

        $email = $token->getClaim('email');

        /** @var User $user */
        $user = User::objects()
            ->filter('email', '=', $email)
            ->single();

        return $user->toJSON();

    }

    public function tokens(DuanApp $app, Request $request)
    {
        return 'tokens';
    }

    public function newToken(DuanApp $app, Request $request)
    {
        return 'a new token';
    }
}