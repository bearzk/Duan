<?php
namespace Duan\Tests;

use Mockery;

abstract class IntegrationTestCase extends BaseTestCase
{
    public function setUp()
    {
        $this->app = get_integration_test_app();
        $this->withOutMiddlewares();
        $this->migrateUp();
    }

    public function tearDown()
    {
        $this->migrateDown();
        Mockery::close();
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
}
