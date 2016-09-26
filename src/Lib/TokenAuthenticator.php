<?php
namespace Duan\Lib;

use Duan\Models\Token;
use Duan\Models\User;

class TokenAuthenticator
{
    /**
     * Authenticate by token id.
     * return corresponding user when found,
     * false when NOT found.
     *
     * @param $id
     * @return bool|User
     */
    public function authenticate($id)
    {
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
