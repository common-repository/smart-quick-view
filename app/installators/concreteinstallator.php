<?php

namespace SmartQuickView\App\Installators;

use SmartQuickView\App\Data\Settings\Settings;
use SmartQuickView\Original\Data\Drivers\WordPressDatabaseDriver;
use SmartQuickView\Original\Environment\Env;
use SmartQuickView\Original\Installation\Installator;

/**
 * Helps us install custom database when applicable.
 */
Class ConcreteInstallator
{
    protected $applicationDatabase;

    public function __construct()
    {
        (string) $ApplicationDatabase = Env::settings()->schema->applicationDatabase;

        $this->applicationDatabase = new $ApplicationDatabase(new WordPressDatabaseDriver);   
    }
    
    public function install()
    {
        $this->applicationDatabase->install();
    }

    public function update()
    {
        $this->applicationDatabase->update();   
    }
}