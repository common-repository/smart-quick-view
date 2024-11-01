<?php

namespace SmartQuickView\App\Modules\Designs;

use SmartQuickView\Original\Collections\Collection;
use SmartQuickView\Original\Presentation\Component;

Abstract Class Design
{
    const COMPONENT_GALLERY = 'gallery';
    const COMPONENT_TITLE = 'title';
    const COMPONENT_RATING = 'rating';
    const COMPONENT_PRICE = 'price';
    const COMPONENT_EXCERPT = 'excerpt';
    const COMPONENT_ADD_TO_CART = 'add_to_cart';
    const COMPONENT_META_ALL = 'meta';
    const COMPONENT_SHARING = 'sharing';

    /**
     * Each design may declare which WooCommerce single template compnents it supports.
     */
    abstract static public function getSupportedComponents() : Collection;
    abstract public function getTemplateComponent(int $productId) : Component;
    abstract public function getTemplateSkeletonComponent() : Component;

    public static function allComponents() : Collection 
    {
        return new Collection([
            Design::COMPONENT_GALLERY,
            Design::COMPONENT_TITLE,
            Design::COMPONENT_RATING,
            Design::COMPONENT_PRICE,
            Design::COMPONENT_EXCERPT,
            Design::COMPONENT_ADD_TO_CART,
            Design::COMPONENT_META_ALL,
            Design::COMPONENT_SHARING,
        ]);
    }
}