<?php
namespace Duan\Tests;

use Exception;

trait WithOutExceptionHandling
{
    protected function withOutExceptionHandling()
    {
        $app = $this->app();
        $app->exception(function (Exception $e) use ($app) {
            throw $e;
        });
    }
}
