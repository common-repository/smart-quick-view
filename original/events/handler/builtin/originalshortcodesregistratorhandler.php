<?php

namespace SmartQuickView\Original\Events\Handler\BuiltIn;

use SmartQuickView\Original\Environment\Env;
use SmartQuickView\Original\Events\Handler\EventHandler;

Class OriginalShortCodesRegistratorHandler extends EventHandler
{
    protected $numberOfArguments = 1;
    protected $priority = 10;

    public function execute()
    {
        foreach ($this->getRegisteredShortcodes() as $ShortCode) {
            add_shortcode($ShortCode::name(), $ShortCode::handle());
        }
    }

    protected function getRegisteredShortcodes()
    {
        return $this->cache->getIfExists('registeredShortcodes')->otherwise(function(){
            return require Env::appDirectory('shortcodes').'register.php';
        });   
    }
    
}