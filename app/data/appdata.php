<?php

namespace SmartQuickView\App\Data;

use SmartQuickView\App\Components\DefaultPopup;
use SmartQuickView\App\Components\DefaultPopupSkeleton;
use SmartQuickView\App\Data\Preferences\Preferences;
use SmartQuickView\Original\Collections\Collection;
use SmartQuickView\Original\Environment\Env;

Class AppData
{
    public function export() : Collection
    {
        (object) $preferences = Preferences::get();
        (object) $currentDesign = $preferences->getCurrentDesign();

        return new Collection([
            'urls' => [
                'adminAJAX' => esc_url(admin_url('admin-ajax.php')),
            ],
            'textDomain' => esc_html(Env::settings()->app->textDomain),
            'template' => [
                'skeleton' => $currentDesign->getTemplateSkeletonComponent()
                                            ->getRenderedMarkup()
            ],
            'actions' => [
                'getTemplate' => 'sqv_product_template'
            ],
            'publicPreferences' => [
                'styles' => [
                    'blurIsEnabled' => (boolean) $preferences->preferences->style_blur_isEnabled
                ],
            ],
            'app' => [
                'classes' => [
                    'contentElement' => esc_attr($currentDesign->getClasses()->get('contentElement'))
                ]
            ],
            'productsData' => (object) [
                /*11 => [
                    'template' => ($currentDesign->getTemplateComponent(11))->getRenderedMarkup()
                ],
                13 => [
                    'template' => ($currentDesign->getTemplateComponent(13))->getRenderedMarkup()
                ],
                39 => [
                    'template' => ($currentDesign->getTemplateComponent(39))->getRenderedMarkup()
                ],
                45 => [
                    'template' => ($currentDesign->getTemplateComponent(45))->getRenderedMarkup()
                ],
                46 => [
                    'template' => ($currentDesign->getTemplateComponent(46))->getRenderedMarkup()
                ],
                47 => [
                    'template' => ($currentDesign->getTemplateComponent(47))->getRenderedMarkup()
                ],
                48 => [
                    'template' => ($currentDesign->getTemplateComponent(48))->getRenderedMarkup()
                ]*/
            ]
        ]);
    }
    
}