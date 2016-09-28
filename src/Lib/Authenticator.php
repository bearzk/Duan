<?php
namespace Duan\Lib;

use Duan\Models\User;
use Symfony\Component\HttpFoundation\Request;

class Authenticator
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
        // TODO
        // success User
        // fail false
    }
}
