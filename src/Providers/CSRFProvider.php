<?php
namespace Duan\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Schnittstabil\Csrf\TokenService\TokenService;

class CSRFProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['csrf'] = function () use ($container) {
            $config = $container->getConfig();
            if (empty($config['csrf_key'])) {
                throw new \Exception("csrf_key not set in config");
            }

            $tokenService = new TokenService($config['csrf_key']);

            return $tokenService;
        };
    }
}