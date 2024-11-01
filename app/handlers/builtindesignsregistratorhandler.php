<?php

namespace SmartQuickView\App\Handlers;

use SmartQuickView\App\Modules\DesignsRegistrator;
use SmartQuickView\App\Modules\Designs\BuiltIn\DefaultDesign;
use SmartQuickView\App\Modules\Designs\BuiltIn\VerticalDesign;
use SmartQuickView\Original\Events\Handler\EventHandler;

Class BuiltInDesignsRegistratorHandler extends EventHandler
{
    protected $numberOfArguments = 1;
    protected $priority = 10;

    /**
     * We need to always pass or we will find ourselves in
     * an endless loop
     */
    public function canBeExecuted() : bool
    {
        return true;   
    }
    
    public function execute(DesignsRegistrator $designsRegistrator)
    {
        $designsRegistrator->register(DefaultDesign::class);

        /**
         * Not ready yet, it's currently in alpha state. todo: fix css
         * $designsRegistrator->register(VerticalDesign::class);
         */
    }
}