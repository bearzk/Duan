<?php
namespace Duan\Models;

use Duan\Exceptions\InvalidArgumentException;
use Duan\Lib\JWTFacade;

class User extends BaseModel
{
    protected static $_meta = [
        'database' => 'duan',
        'table' => 'users',
        'pk' => 'id'
    ];

    public $id;
    public $alias;
    public $email;
    public $password;
    public $first_name;
    public $last_name;

    public function __construct()
    {
        parent::__construct();
    }

    public function tokens()
    {
        $tokens = Token::objects()
            ->filter('user_id', '=', $this->id)
            ->fetch();

        return $tokens;
    }

    public static function create(JWTFacade $jwt, $email, $password, $alias, $firstName = null, $lastName = null, $withToken = false, $tokenName = null)
    {
        $user = new static;

        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_BCRYPT);
        $user->alias = $alias;

        self::validate([
            $user->email,
            $user->password,
            $user->alias
        ]);

        if (!is_null($firstName)) {
            $user->first_name = $firstName;
        }

        if (!is_null($lastName)) {
            $user->last_name = $lastName;
        }

        $user->save();

        if ($withToken) {
            Token::create($jwt, $user, $tokenName);
        }

        return $user;
    }
}
