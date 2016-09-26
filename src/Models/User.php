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
    public $firstName;
    public $lastName;

    public function __construct()
    {
        parent::__construct();
    }

}
