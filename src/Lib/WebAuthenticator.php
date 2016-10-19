<?php
namespace Duan\Lib;

use Duan\DuanApp;
use Duan\Models\User;
use Symfony\Component\HttpFoundation\Request;

class WebAuthenticator
{
    /** @var JWTFacade $jwt */
    private $jwt;

    public function __construct(DuanApp $app)
    {
        $this->jwt = $app['jwt'];
    }

    /**
     * Authenticate by email password combination
     * return corresponding user when found,
     * false when NOT found.
     *
     * @param Request $request
     * @return bool|User
     */
    public function auth(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        /** @var User $user */
        $user = User::objects()
            ->filter('email', '=', $email)
            ->single(true);

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }
}
