<?php
namespace Duan\Models;
use Phormium\Model;

class User extends Model
{
    protected static $_meta = [
        'database' => 'duan',
        'table' => 'users',
        'pk' => 'id'
    ];

    public $id;
    public $alias;
    public $email;
    public $fisrtName;
    public $lastName;
}
