<?php

namespace SmartQuickView\App\Modules\Designs\BuiltIn;

use SmartQuickView\App\Components\DefaultPopup;
use SmartQuickView\App\Components\DefaultPopupSkeleton;
use SmartQuickView\App\Components\VerticalPopup;
use SmartQuickView\App\Modules\Designs\Design;
use SmartQuickView\Original\Collections\Collection;
use SmartQuickView\Original\Presentation\Component;

/**
 * This design is coming soon.
 * The plugin will have 2 different designs available to everyone
 * but right now this one is in currently development.
 */
Class VerticalDesign extends Design
{
    const TYPE = 'default';

    public static function getSupportedComponents() : Collection
    {
        return static::allComponents();
    }

    public function getTemplateComponent(int $productId) : Component
    {
        return new VerticalPopup($productId);
    }

    public function getTemplateSkeletonComponent() : Component
    {
        return new DefaultPopupSkeleton;
    }

    public function getClasses() : Collection
    {
        return new Collection([
            'contentElement' => 'bg-white 
                                mobile:w-[96%] mobile:max-w-[500px] mobile:max-h-[840px] 
                                tablet:max-w-initial tablet:max-h-auto tablet:w-[560px] tablet:max-h-[94%] 
                                focus:outline-none'
        ]);   
    }
}