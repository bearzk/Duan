<?php
namespace Duan\Lib;


class UrlValidator
{
    /**
     * @param $url
     * @return bool
     */
    public static function validate($url)
    {
        $url = filter_var($url, FILTER_VALIDATE_URL);
        return !empty($url);
    }
}
