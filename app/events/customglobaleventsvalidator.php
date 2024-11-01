<?php

namespace SmartQuickView\App\Events;

use DeviceDetector\DeviceDetector;
use SmartQuickView\App\Data\Preferences\Preferences;
use SmartQuickView\Original\Cache\MemoryCache;
use SmartQuickView\Original\Environment\Env;
use SmartQuickView\Original\Events\Handler\GlobalEventsValidator;

Class CustomGlobalEventsValidator extends GlobalEventsValidator
{
    protected static $messageHasBeenRegistered = false;

    public function canBeExecuted() : bool
    {
        // NEVER DISABLE WHEN ON ADMIN
        if (is_admin() || is_customize_preview()) {
            return true;
        }

        (boolean) $WooCommerceIsActive = class_exists(\WooCommerce::class);

        if ($WooCommerceIsActive) {
            (object) $preferences = Preferences::get()->preferences;
            // cannot use wp_is_mobile() since it doesn't detect tablets.
            (object) $deviceDetector = new DeviceDetector(sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])));

            $deviceDetector->parse();

            (string) $device = $deviceDetector->getDeviceName();
            (string) $currentDevice = in_array($device, ['smartphone', 'feature phone', 'phablet'])? 'smartphone' : $device;

            return $preferences->devices_enabled->contain($currentDevice);
        }

        if (!static::$messageHasBeenRegistered) {
            static::$messageHasBeenRegistered = true;

            add_action( 'admin_notices', function () {
                (string) $message = __('Almost Ready! WooCommerce needs to be installed and activated for '.Env::settings()->app->name.' to work.', 'coupons-plus-international');
             
                print '<div class="notice notice-error"><p>'.esc_html($message).'</p></div>';
            });

        }
        
        return false;
    }
}