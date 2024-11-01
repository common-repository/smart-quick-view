<?php

namespace SmartQuickView\App\Modules;

use SmartQuickView\App\Conditions\Condition;
use SmartQuickView\App\Modules\ComponentsRegistrator;
use SmartQuickView\App\Modules\Designs\Design;

Class DesignsRegistrator extends ComponentsRegistrator
{
    protected static $instance;
    
    protected static function getComponentId() : string
    {
        return 'design';
    }

    protected static function getComponentType() : string
    {
        return Design::class;
    }
}