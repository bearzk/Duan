<?php
namespace Duan\Lib;

use Duan\Models\Token;
use Duan\Models\User;
use Symfony\Component\HttpFoundation\Request;

class TokenAuthenticator
{
    /**
     * Authenticate by token id.
     * return corresponding user when found,
     * false when NOT found.
     *
     * @param Request $request
     * @return bool|User
     */
    public function auth(Request $request)
    {
        $authHeader = $request->headers->get('Authorization', '');
        if (empty($authHeader)) {
            return false;
        }
        $id = explode(" ", $authHeader)[1];
        /** @var Token $token */
        $token = Token::objects()
            ->filter('id', '=', $id)
            ->filter('revoked', '!=', 1)
            ->single(true);

        if (empty($token)) {
            return false;
        } else {
            return $token->getUser();
        }
    }
}
