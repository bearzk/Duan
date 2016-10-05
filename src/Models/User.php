<?php
namespace Duan\Models;

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
}
