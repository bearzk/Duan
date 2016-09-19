<?php
namespace Duan\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

class TwigProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['twig'] = function() use ($container) {
            $root = $container->getProjectRoot();
            $templateDir = $root . '/templates';
            $cacheDir = sys_get_temp_dir() . '/twig/cache';
            $loader = new Twig_Loader_Filesystem($templateDir);
            $config = $container->getConfig();
            $twig = new Twig_Environment($loader, array(
                'cache' => $cacheDir,
                'debug' => $config['debug']
            ));

            if ($container->getEnv() === 'local') {
                $twig->addExtension(new Twig_Extension_Debug());
            }

            $twig->addGlobal('config', $config);

            return $twig;
        };
    }
}
