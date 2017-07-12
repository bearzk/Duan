<?php
namespace Duan\Tests;

use Cicada\Routing\Router;
use Duan\DuanApp;
use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseTestCase extends PHPUnit_Framework_TestCase
{
    /** @var DuanApp */
    protected $app;

    /** @var Response */
    protected $response;

    public function setUp()
    {
        $this->app = get_test_app();
        $this->withOutMiddlewares();
        $this->withOutExceptionHandling();
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    protected function app()
    {
        return $this->app;
    }

    private function createRequest($fullPath, $method = 'GET', $parameters = array(), $server = array(), $content = null)
    {
        $request = Request::create(
            $fullPath,
            $method,
            $parameters,
            [],
            [],
            $server,
            $content
        );
        return $request;
    }

    private function appHandlesRequest(Request $request)
    {
        $this->response  = $this->app->handle($request);
    }

    protected function getRoutePath($routeName, $params = [])
    {
        /** @var Router $router */
        $router = $this->app()['router'];
        return $router->getRoutePath($routeName, $params);
    }

    protected function get($fullPath)
    {
        $request = $this->createRequest($fullPath);
        $this->appHandlesRequest($request);
        return $this;
    }

    protected function getJson($fullPath)
    {
        $server = $this->setContentTypeJson();
        $request = $this->createRequest($fullPath, 'GET', [], $server);
        $this->appHandlesRequest($request);
        return $this;
    }

    protected function postJson($fullPath, $jsonString)
    {
        $server = $this->setContentTypeJson();
        $request = $this->createRequest($fullPath, 'POST', [], $server, $jsonString);
        $this->appHandlesRequest($request);
        return $this;
    }

    protected function post($fullPath, array $parameters)
    {
        $request = $this->createRequest($fullPath, 'POST', $parameters);
        $this->appHandlesRequest($request);
        return $this;
    }

    protected function delete($fullPath, array $parameters)
    {
        $request = $this->createRequest($fullPath, 'DELETE', $parameters);
        $this->appHandlesRequest($request);
        return $this;
    }


    protected function assertSee($string)
    {
        $this->assertRegExp("#$string#", $this->response->getContent());
        return $this;
    }

    protected function assertSeeJson($json)
    {
        $this->assertJsonStringEqualsJsonString($json, $this->response->getContent());
        return $this;
    }

    protected function assertDontSee($string)
    {
        $this->assertEquals(0, preg_match("#$string#", $this->response->getContent()));
        return $this;
    }

    protected function assertSeeTimes($string, $times)
    {
        $matches = null;
        preg_match_all("#$string#", $this->response->getContent(), $matches);
        $this->assertEquals($times, count($matches[0]));
        return $this;
    }

    protected function assertResponseCode($code)
    {
        $this->assertEquals($code, $this->response->getStatusCode());
    }

    private function setContentType($type)
    {
        $server = [];
        $server['CONTENT_TYPE'] = $type;
        return $server;
    }

    private function setContentTypeJson()
    {
        return $this->setContentType('application/json');
    }

    protected function migrateUp()
    {
    }

    protected function migrateDown()
    {
    }

    protected function withOutMiddlewares()
    {
    }

    protected function withOutExceptionHandling()
    {
    }
}
