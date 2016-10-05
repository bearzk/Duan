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
}
