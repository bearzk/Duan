<?php
namespace Duan\Providers;

use Duan\Lib\JWTFacade;
use Lcobucci\JWT\Signer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class JWTProvider implements ServiceProviderInterface
{
    public function register(Container $container, Signer $signer = null)
    {
        $jwtConfig = $container->getConfig()['jwt'];
        $container['jwt'] = new JWTFacade($jwtConfig, $signer);
    }
}
