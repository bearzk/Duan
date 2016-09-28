<?php
namespace Duan\Lib;

class Hash
{
    public static function url($url)
    {
        $head = rand(0, 80);
        $hash = substr(
            preg_replace(
                '/[_\/=]+/',
                '',
                base64_encode(hash('sha256', $url))),
            $head, 6);
        return $hash;
    }
}
