<?php

namespace SmartQuickView\Original\Installation;

use SmartQuickView\App\Installators\ConcreteInstallator;
use SmartQuickView\Original\Environment\Env;

Class Installator
{
    public function __construct()
    {
        register_activation_hook(
            Env::absolutePluginFilePath(), 
            [new ConcreteInstallator, 'install']
        );
    }
}