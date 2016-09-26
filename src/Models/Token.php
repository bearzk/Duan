<?php
namespace Duan\Models;

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
    public $revoked;
    public $expired_at;

    public function __construct()
    {
        parent::__construct();
    }

    public function getUser()
    {
        return User::objects()
          ->filter('user_id', '=', $this->user_id)
          ->single(ture);
    }
}
