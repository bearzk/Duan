<?php
namespace Duan\Models;

use Duan\DuanApp;
use Duan\Lib\JWTFacade;

class Token extends BaseModel
{
    protected static $_meta = [
        'database' => 'duan',
        'table' => 'tokens',
        'pk' => 'id'
    ];

    public $id;
    public $user_id;
    public $name;
    public $revoked = 0;
    public $expired_at;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        return implode(" ", [$this->id, $this->expired_at]);
    }

    public function getUser()
    {
        return User::objects()
          ->filter('id', '=', $this->user_id)
          ->single(true);
    }

    public static function create(JWTFacade $jwt, User $user, $name, $expireInDays = 0)
    {
        $token = new static;
        $token->user_id = $user->id;
        $token->id = (string) $jwt->build(['email' => $user->email], $expireInDays);
        $token->name = $name;

        $token->save();
        return $token;
    }
}
