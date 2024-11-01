<?php

namespace SmartQuickView\Original\Data\Schema;

use SmartQuickView\Original\Data\Drivers\DatabaseDriver;
use SmartQuickView\Original\Data\Schema\DatabaseTable;
use SmartQuickView\Original\Utilities\TypeChecker;
use SmartQuickView\Original\Utilities\className;

Class DatabaseSchema
{
    use className;
    use TypeChecker;

    protected $driver;
    protected $tables;

    public function __construct(DatabaseDriver $driver)
    {
        $this->driver = $driver;
        $this->tables = $this->expectEach($this->tables())->toBe(DatabaseTable::class);
    }

    public function install()
    {
        $this->applyToEach($this->tables, 'install');
    }

    public function update()
    {
        $this->applyToEach($this->tables, 'update');
    }

    public function uninstall()
    {
        $this->applyToEach($this->tables, 'uninstall');
    }

    public function reinstall()
    {
        $this->uninstall();
        $this->install();
    }

    protected function applyToEach(array $tables, $action)
    {
        foreach ($tables as $table) {
            $this->driver->{$action}($table);
        }
    }
}