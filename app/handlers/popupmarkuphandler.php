<?php

namespace SmartQuickView\App\Handlers;

use SmartQuickView\App\Components\PopupMarkup;
use SmartQuickView\Original\Events\Handler\EventHandler;

Class PopupMarkupHandler extends EventHandler
{
    protected $numberOfArguments = 1;
    protected $priority = 10;

    public function execute()
    {
        (object) $popupMarkup = new PopupMarkup;   

        $popupMarkup->render();
    }
}