<?php

namespace SmartQuickView\App\Modules\Designs\BuiltIn;

use SmartQuickView\App\Components\DefaultPopup;
use SmartQuickView\App\Components\DefaultPopupSkeleton;
use SmartQuickView\App\Modules\Designs\Design;
use SmartQuickView\Original\Collections\Collection;
use SmartQuickView\Original\Presentation\Component;

Class DefaultDesign extends Design
{
    const TYPE = 'default';

    public static function getSupportedComponents() : Collection
    {
        return static::allComponents();
    }

    public function getTemplateComponent(int $productId) : Component
    {
        return new DefaultPopup($productId);
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
                                tablet:max-w-initial tablet:max-h-auto tablet:w-[760px] tablet:h-[500px] 
                                desktop:w-[900px] desktop:h-[550px] 
                                focus:outline-none'
        ]);   
    }
}