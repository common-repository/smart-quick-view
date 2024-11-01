<?php

return [
    'wp_enqueue_scripts' => [
        'SmartQuickView\\App\\Handlers\\ScripsRegistratorHandler',
    ],
    'wp_footer' => [
        'SmartQuickView\\App\\Handlers\\PopupMarkupHandler',
    ],
    'init' => [
        'SmartQuickView\\App\\Handlers\\PopupButtonRendererHandler',
        'SmartQuickView\\App\\Handlers\\KirkiHandler',
    ],
    'wp_ajax_sqv_product_template' => [
        'SmartQuickView\\App\\Handlers\\ProductAPIHandler',
    ],
    'wp_ajax_nopriv_sqv_product_template' => [
        'SmartQuickView\\App\\Handlers\\ProductAPIHandler',
    ],
    'smartquickview_register_design_component' => [
        'SmartQuickView\\App\\Handlers\\BuiltInDesignsRegistratorHandler',
    ],
];