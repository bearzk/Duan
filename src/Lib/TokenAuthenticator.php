<?php
namespace Duan\Lib;

class TokenAuthenticator
{
    public function authenticate($id)
    {
        return !empty(Token::find($id));
    }
}
