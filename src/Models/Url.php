<?php
namespace Duan\Models;

use Carbon\Carbon;
use Duan\Lib\Hash;
use Phormium\Model;

/**
 * Class Url
 * @package Duan\Models
 */
class Url extends BaseModel
{
    protected static $_meta = [
        'database' => 'duan',
        'table' => 'urls',
        'pk' => 'hash'
    ];

    /**
     * @var string hash
     */
    public $hash;
    /**
     * @var string url
     */
    public $url;
    /**
     * @var bool customized
     */
    public $customized = 0;

    public function __construct()
    {
        parent::__construct();
    }

    public static function getByUrl($url)
    {
        return static::objects()
            ->filter('url', '=', $url)
            ->filter('customized', '!=', true)
            ->single(true);
    }

    public static function create(array $params)
    {
        if (!empty($params['hash'])) {
            $h = $params['hash'];
        }

        $u = $params['url'];

        if (empty($h)) {
            $url = static::getByUrl($u);
        } else {
            $url = static::find($h);
        }

        if (empty($url)) {
            $url = new static();

            $url->url = $params['url'];
            if (empty($params['hash'])) {
                $url->hash = Hash::url($params['url']);
            } else {
                $url->hash = $params['hash'];
                $url->customized = 1;
            }

            $url->save();
        }

        return $url;
    }

    public static function keys()
    {
        return get_object_vars(new static());
    }
}
