<?php
namespace Duan\Tests;

use Cicada\Routing\Route;
use Cicada\Routing\Router;

trait WithOutMiddlewares
{
    protected function withOutMiddlewares()
    {
        $this->app()->clearMiddlewares();

        // routes can have individual before after functions
        // we have to clean them as well
        /** @var Router $router */
        $router = $this->app()['router'];
        $routes = $router->getRoutes();
        foreach ($routes as $route) {
            /** @var Route $route */
            $route->clearMiddlewares();
        }
    }
}
