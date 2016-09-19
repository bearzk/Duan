<?php
namespace Duan\Providers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LoggerProvider implements ServiceProviderInterface
{
    public function register(Container $container, $logPath = null)
    {
        $container['logger'] = function () use ($container, $logPath) {
            $logPath = is_null($logPath) ? $container->getProjectRoot() . '/log/duan.log' : $logPath;
            $logger = new Logger('duan');
            $config = $container->getConfig();
            $logLevel = $config['debug'] ? Logger::DEBUG : Logger::WARNING;
            $logger->pushHandler(new StreamHandler($logPath, $logLevel));
            return $logger;
        };
    }
}
