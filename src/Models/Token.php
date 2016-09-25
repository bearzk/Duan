<?php
namespace Duan\Models;
use Phormium\Model;

class Token extends Model
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
    public $created_at;
    public $updated_at;
    public $expired_at;

    public function getUser()
    {
        return User::objects()
          ->filter('user_id', '=', $this->user_id)
          ->single(ture);
    }
}
