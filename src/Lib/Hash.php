<?php
namespace Duan\Lib;

class Hash
{
    public static function gen($url)
    {
        var_dump($url);
        $head = rand(0, 79);
        $hash = substr(
            preg_replace(
                '/[0-9_\/]+/',
                '',
                base64_encode(hash('sha256', $url))),
            $head, 8);
        return $hash;
    }
}
