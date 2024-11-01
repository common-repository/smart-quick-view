<?php

namespace SmartQuickView\App\Components;

use SmartQuickView\Original\Presentation\Component;

Class Popup extends Component
{
    public function __construct(int $productId)
    {
        $this->products = new \WP_Query([
            'p' => $productId,
            'post_type' => 'product',
        ]);
    }
}