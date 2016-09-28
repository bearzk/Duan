<?php
namespace Duan\Providers\AuthProviders;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Duan\Lib\Authenticator;

class WebAuthProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['auth'] = function () use ($container) {
          return new Authenticator;
        };
    }

}
