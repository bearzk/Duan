<?php
namespace Duan\Tests;

use Cinesearch\Web\Tests\Commands\Migration\DestroyCommand;
use Cinesearch\Web\Tests\Commands\Migration\MigrateCommand;

trait WithMigrations
{
    protected function migrateUp()
    {
        $command = $this->createMigrateCommand();
        $this->getCommandBus()->handle($command);
    }

    protected function migrateDown()
    {
        $command = $this->createDestroyCommand();
        $this->getCommandBus()->handle($command);
    }

    private function createMigrateCommand()
    {
        $command = new MigrateCommand();
        $command->manualMigrationPath = $this->app()->getProjectRoot() . '/tests/assets/sql/migrations/ups/';
        return $command;
    }

    private function createDestroyCommand()
    {
        $command = new DestroyCommand();
        $command->manualMigrationPath = $this->app()->getProjectRoot() . '/tests/assets/sql/migrations/downs/';
        return $command;
    }

    /**
     * @return \League\Tactician\CommandBus
     */
    private function getCommandBus()
    {
        return $this->app()['commandBus'];
    }
}
