<?php

namespace SmartQuickView\App\Handlers;

use SmartQuickView\App\Components\PopupButton;
use SmartQuickView\App\Data\Preferences\Preferences;
use SmartQuickView\Original\Events\Handler\EventHandler;

Class PopupButtonRendererHandler extends EventHandler
{
    protected $numberOfArguments = 1;
    protected $priority = 10;

    public function execute()
    {
        add_action(
            'woocommerce_after_shop_loop_item', 
            [$this, 'render'],
            Preferences::get()->preferences->button_display_position->is('left')? 10 - 1 : 10 + 1
        );
    }

    public function render()
    {
        (object) $popupButton = new PopupButton;       

        $popupButton->render();   
    }
    
}