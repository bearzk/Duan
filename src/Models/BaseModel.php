<?php
namespace Duan\Models;

use Carbon\Carbon;
use Duan\Exceptions\InvalidArgumentException;
use Phormium\Model;

class BaseModel extends Model
{
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        $this->created_at = Carbon::now();
        $this->updated_at = Carbon::now();
    }

    public static function validate(...$arguments)
    {
        foreach($arguments as $argument) {
            if (empty($argument)) {
                throw new InvalidArgumentException;
            }
        }
    }
}
