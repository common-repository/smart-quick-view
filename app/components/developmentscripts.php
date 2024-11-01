<?php

namespace SmartQuickView\App\Components;

use SmartQuickView\Original\Presentation\Component;

Class DevelopmentScripts extends Component
{
    protected $file = 'developmentScripts.php';

    public function __construct(string $appId)
    {
        $this->appId = $appId;   
    }
}