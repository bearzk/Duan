<?php
namespace Duan\Models;

use Phormium\Model;

/**
 * Class Url
 * @package Duan\Models
 */
class Url extends Model
{
    protected static $_meta = [
        'database' => 'duan',
        'table' => 'urls',
        'pk' => 'h'
    ];

    /**
     * @var string hash
     */
    public $h;
    /**
     * @var string url
     */
    public $u;
    /**
     * @var bool customized
     */
    public $c;

    public static function getByHash($hash)
    {
        return static::objects()
            ->filter('h', '=', $hash)
            ->single(true);
    }

    public static function getByUrl($url)
    {
        return static::objects()
            ->filter('u', '=', $url)
            ->filter('c', '<>', true)
            ->single(true);
    }
}
