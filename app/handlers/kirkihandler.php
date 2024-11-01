<?php

namespace SmartQuickView\App\Handlers;

use Kirki\Compatibility\Kirki;
use Kirki\Field\Checkbox;
use Kirki\Field\Checkbox_Switch;
use Kirki\Field\Color;
use Kirki\Field\Multicheck;
use Kirki\Field\Radio_Buttonset;
use Kirki\Field\Text;
use Kirki\Panel;
use Kirki\Section;
use SmartQuickView\App\Data\Preferences\Preferences;
use SmartQuickView\Original\Collections\Collection;
use SmartQuickView\Original\Environment\Env;
use SmartQuickView\Original\Events\Handler\EventHandler;

Class KirkiHandler extends EventHandler
{
    const CONFIGURATION_ID = 'smartquickview_configuration';
    const PANEL_ID = 'smartquickview_panel';

    protected $numberOfArguments = 1;
    protected $priority = 10;

    public function execute()
    {
        $this->loadKirkiIfNeeded();

        Kirki::add_config(static::CONFIGURATION_ID, [
            'option_type'   => 'option',
            'option_name' => Preferences::KEY
        ]);

        new Panel(
            static::PANEL_ID,
            [
                'title'       => esc_html(Env::settings()->app->name),
                'description' => esc_html(__('Personalize your Smart Quick View plugin.', 'smartquickview-international')),
            ]
        );

        (object) $preferencesMap = Preferences::getOptions();

        $this->registerButtonPereferences($preferencesMap);
        $this->registerDevicesPereferences($preferencesMap);
        $this->registerStylesPereferences($preferencesMap);
    }

    protected function registerButtonPereferences(Collection $preferencesMap)
    {
        (string) $sectionId = Env::getwithPrefix('button');

        new Section(
            $sectionId,
            [
                'panel' => static::PANEL_ID,
                'title'       => esc_html(__('Button', 'smartquickview-international')),
                'description' => esc_html(__('The button that opens the quick view popup.', 'smartquickview-international')),
                'priority'    => 10,
            ]
        );

        new Radio_Buttonset(
            [
                'option_type'   => 'option',
                'option_name' => Preferences::KEY,
                'settings'    => 'button_display_position',
                'label'       => esc_html(__('Position', 'smartquickview-international')),
                'section'     => $sectionId,
                'default'     => $preferencesMap->get('button_display_position')->getDefaultValue(),
                'priority'    => 10,
                'choices'     => $preferencesMap->get('button_display_position')->getAllowedValues()->reverse()->asArray(),
            ]
        );

        new Text(
            [
                'option_type'   => 'option',
                'option_name' => Preferences::KEY,
                'settings' => 'button_text',
                'label'    => esc_html(__('Button Text', 'smartquickview-international')),
                'section'  => $sectionId,
                'default'  => $preferencesMap->get('button_text')->getDefaultValue(),
                'priority' => 10,
            ]
        );

        new Checkbox_Switch(
            [
                'option_type'   => 'option',
                'option_name' => Preferences::KEY,
                'settings' => 'button_colors_isEnabled',
                'label'       => esc_html(__('Custom Colors', 'smartquickview-international')),
                'description' => esc_html(__('If disabled, used your own theme colors for the button.', 'smartquickview-international')),
                'section'     => $sectionId,
                'default'     => $preferencesMap->get('button_colors_isEnabled')->getDefaultValue()? 'on' : 'off',
                'choices'     => [
                    'on'  => esc_html(__('Enabled', 'smartquickview-international')),
                    'off' => esc_html(__('Disabled', 'smartquickview-international')),
                ],
            ]
        );

        new Color(
            [
                'option_type'   => 'option',
                'option_name' => Preferences::KEY,
                'settings'    => 'button_colors_background',
                'label'       => esc_html(__('Background Color', 'smartquickview-international')),
                'section'     => $sectionId,
                'default'     => $preferencesMap->get('button_colors_background')->getDefaultValue(),
            ]
        );

        new Color(
            [
                'option_type'   => 'option',
                'option_name' => Preferences::KEY,
                'settings'    => 'button_colors_text',
                'label'       => esc_html(__('Text Color', 'smartquickview-international')),
                'section'     => $sectionId,
                'default'     => $preferencesMap->get('button_colors_text')->getDefaultValue(),
            ]
        );
    }

    protected function registerDevicesPereferences(Collection $preferencesMap)
    {
        (string) $sectionId = Env::getwithPrefix('devices');

        new Section(
            $sectionId,
            [
                'panel' => static::PANEL_ID,
                'title'       => esc_html(__('Devices (Responsive)', 'smartquickview-international')),
                'description' => esc_html(__('Responsive Preferences.', 'smartquickview-international')),
                'priority'    => 10,
            ]
        );

        new Multicheck(
            [
                'option_type'   => 'option',
                'option_name' => Preferences::KEY,
                'settings' => 'devices_enabled',
                'label'    => esc_html(__('Enable on:', 'smartquickview-international')),
                'section'  => $sectionId,
                'default'  => $preferencesMap->get('devices_enabled')->getDefaultValue(),
                'priority' => 10,
                'choices'  => $preferencesMap->get('devices_enabled')->getAllowedValues()->reverse()->asArray(),
            ]
        );
    }

    protected function registerStylesPereferences(Collection $preferencesMap)
    {
        (string) $sectionId = Env::getwithPrefix('style');

        new Section(
            $sectionId,
            [
                'panel' => static::PANEL_ID,
                'title'       => esc_html(__('Style', 'smartquickview-international')),
                'priority'    => 10,
            ]
        );

        new Checkbox_Switch(
            [
                'option_type'   => 'option',
                'option_name' => Preferences::KEY,
                'settings' => 'style_blur_isEnabled',
                'label'       => esc_html(__('Glass effect (blur)', 'smartquickview-international')),
                'section'     => $sectionId,
                'default'     => $preferencesMap->get('style_blur_isEnabled')->getDefaultValue()? 'on' : 'off',
                'choices'     => [
                    'on'  => esc_html(__('Enabled', 'smartquickview-international')),
                    'off' => esc_html(__('Disabled', 'smartquickview-international')),
                ],
            ]
        );
    }
    
    protected function loadKirkiIfNeeded()
    {
        if (!defined('KIRKI_VERSION') || strpos((string) KIRKI_VERSION, '4') !== 0) {
            require_once Env::directory().'/vendor/kirki/kirki.php';
        }
    }
    
}