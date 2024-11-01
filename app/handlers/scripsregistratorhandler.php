<?php

namespace SmartQuickView\App\Handlers;

use SmartQuickView\App\Components\DevelopmentScripts;
use SmartQuickView\App\Data\AppData;
use SmartQuickView\App\Data\Preferences\Preferences;
use SmartQuickView\Original\Collections\Collection;
use SmartQuickView\Original\Environment\Env;
use SmartQuickView\Original\Events\Handler\EventHandler;

Class ScripsRegistratorHandler extends EventHandler
{
    protected $numberOfArguments = 1;
    protected $priority = 10;

    public function execute()
    {
        wp_enqueue_style(
            Env::getwithPrefix('styles'), 
            Env::directoryURI().'/app/scripts/app/styles/build/app-production.css',
            null,
            $version = Env::settings()->environment === 'development'? time() : ''
        );

        (string) $buttonBackgroundColor = sanitize_text_field(wp_unslash(Preferences::get()->preferences->button_colors_background->get()));
        (string) $buttonTextColor = sanitize_text_field(wp_unslash(Preferences::get()->preferences->button_colors_text->get()));

        wp_add_inline_style(
            Env::getwithPrefix('styles'), 
            "
                :root {
                    --sqv-button-background-color: {$buttonBackgroundColor};
                    --sqv-button-text-color: {$buttonTextColor};
                }
            "
        );

        (boolean) $loadProduction = Env::settings()->environment === 'production' || (isset($_GET['production']) && sanitize_text_field(wp_unslash($_GET['production'])) === 'true');
        (string) $appId = Env::getWithPrefix('app');

        if ($loadProduction) {
            (object) $assetsData = $this->getAssets();

            wp_enqueue_script(
                $id = $appId, 
                $source = Env::directoryURI()."app/scripts/app/build{$assetsData->get('files')->{'main.js'}}",
                $dependencies = ['jquery', 'wp-i18n', 'wc-single-product', 'zoom', 'flexslider', 'photoswipe', 'photoswipe-ui-default'], 
                $version = false, // doesnt matter, the version is in the file name
                $inFooter = true
            );

            wp_enqueue_style( 'photoswipe-default-skin' );
            add_action( 'wp_footer', 'woocommerce_photoswipe' );

            wp_set_script_translations($appId, 'coupons-plus-international');
        } else {
            // ONLY USED FOR THE DEVELOPMENT SCRIPTS, 
            // THIS IS IGNORED ON LIVE SITES
            // BECAUSE THIS CLASS IS NOT INCLUDED IN THE PRODUCTION BUILD.
            if (class_exists(DevelopmentScripts::class)) {
                (object) $developmentScripts = new DevelopmentScripts($appId);
                $developmentScripts->render();
            }
        }

        (object) $appData = new AppData;

        wp_localize_script(
            $appId, 
            'SmartQuickView', 
            $appData->export('CouponsPlus')->asArray()
        );
    }

    protected function getAssets()
    {
        return new Collection((array) json_decode(
            file_get_contents(Env::getAppDirectory('scripts').'app/build/asset-manifest.json')
        ));;
    }    
}