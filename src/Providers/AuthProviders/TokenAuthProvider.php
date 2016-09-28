<?php
namespace Duan\Providers\AuthProviders;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Duan\Lib\TokenAuthenticator;

class TokenAuthProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['token_auth'] = function () use ($container) {
          return new TokenAuthenticator;
        };
    }

}
