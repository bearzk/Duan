<?php
namespace Duan\Providers\AuthProviders;
use Duan\Lib\WebAuthenticator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class WebAuthProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['auth'] = function () use ($container) {
          return new WebAuthenticator($container);
        };
    }

}
