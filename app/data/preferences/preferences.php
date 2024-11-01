<?php

namespace SmartQuickView\App\Data\Preferences;

use SmartQuickView\App\Modules\DesignsRegistrator;
use SmartQuickView\App\Modules\Designs\BuiltIn\DefaultDesign;
use SmartQuickView\App\Modules\Designs\BuiltIn\VerticalDesign;
use SmartQuickView\App\Modules\Designs\Design;
use SmartQuickView\Original\Collections\Collection;
use SmartQuickView\Original\Collections\Mapper\Mappable;
use SmartQuickView\Original\Collections\Mapper\Types;

Class Preferences extends Mappable
{
    const KEY = 'smartquickview_preferences';

    private static $instance;

    public $preferences;

    public static function getOptions() : Collection
    {
        $op = (new Collection([
            /**
             * Button
             */
            'button_display_position' => Types::STRING()->withDefault('right')
                                                        ->allowed([
                                                            __('Before Add to Cart', 'smartquickview-international') => 'left',
                                                           __('After Add to Cart', 'smartquickview-international') => 'right'
                                                        ]),
                                              // no need to internationalize it since it's
                                              // editable via preferences panel.
            'button_text' => Types::STRING()->withDefault('Quick View'),
            'button_colors_isEnabled' => Types::BOOLEAN()->withDefault(false),
            'button_colors_background' => Types::STRING()->withDefault('#000000'),
            'button_colors_text' => Types::STRING()->withDefault('#f8f8f8'),
            /**
             * Devices
             */
            'devices_enabled' => Types::Collection()->withDefault(
                                                        ['smartphone', 'tablet', 'desktop']
                                                    )
                                                    ->allowed([
                                                        __('Smartphone', 'smartquickview-international') => 'smartphone', 
                                                        __('Tablet', 'smartquickview-international') => 'tablet', 
                                                        __('Desktop', 'smartquickview-international') => 'desktop',
                                                    ]),
            /**
             * Styles
             */
            'style_blur_isEnabled' => Types::BOOLEAN()->withDefault(true),
            /**
             * Designs
             */
            'designs_current' => Types::STRING()->withDefault(DefaultDesign::TYPE)
                                                ->allowed(DesignsRegistrator::get()->all()->asArray()),
        ]))->merge(DesignsRegistrator::get()->all()->mapWithKeys(function(string $className, string $type) : array {
            return [
                'key' => "designs_{$type}_componentsEnabled",
                'value' => Types::COLLECTION()->withDefault($className::getSupportedComponents()->asArray())
                                              ->allowed($className::getSupportedComponents()->asArray())
            ];
        }));

        return $op;
    }

    public static function get()
    {
        if (!(static::$instance instanceof self)) {
            static::$instance = new self(get_option(static::KEY));
        }

        return static::$instance;        
    }

    /**
     * Constructs a new preferences object
     * Maps values to fields in self::components()
     * DATABASE QUERIES MUST NOT BE MADE IN THE CONSTRUCTOR
     */
    public function __construct($preferences)
    {
        $this->preferences = $this->map($preferences);
    }

    public function getCurrentDesign() : Design
    {
        (object) $Design = DesignsRegistrator::get()->all()->get($this->preferences->designs_current);   

        return new $Design;
    }

    public function getEnabledComponentsForCurrentDesign() : Collection
    {
        return $this->preferences->{"designs_{$this->preferences->designs_current}_componentsEnabled"};
    }

    protected function getMap()
    {
        return static::getOptions()->asArray();
    }

    public function save()
    {
        (boolean) $updateResult = update_option(
            $name = static::KEY, 
            $value = $this->unMap(), 
            $autoLoad = true
        );      

        static::reset();
        wp_cache_delete('alloptions', 'options');

        return $updateResult;
    }

    public static function reset()
    {
        static::$instance = null;   
    }

    public static function factoryReset()
    {
        (boolean) $updateResult = update_option(
            $name = static::KEY, 
            $value = (new self(''))->unMap(), 
            $autoLoad = true
        );    

        static::reset();

        return $updateResult;
    }

    public function export()
    {
        return $this->unMap();   
    }

    public function exportWithAllowedValues()
    {
        (object) $components = new Collection([]);

        foreach ($this->getValuesToUnmap()->asArray() as $componentName => $component) {
            $components->add(
                $componentName, 
                $component->getValuesToUnmap()->append(
                    [
                        '_allowed' => $component->getFieldsWithAllowedValues(),
                        '_default' => $component->getFieldsWithDefaultValues()
                    ]
                )
            );
        }

        return $components->asJson()->get();
    }
    
    public function getFieldsWithDefaultValues()
    {
        return $this->getFieldsWith(function($field) {
            return $field->getDefaultValue();
        });
    }

    public function getFieldsWithAllowedValues()
    {
        return $this->getFieldsWith(function($field){
            return $field->getAllowedValues();
        });
    }

    protected function getFieldsWith(callable $fieldValueGetter)
    {
        (object) $fields = new Collection([]);

        foreach ($this->getMap() as $fieldName => $field) {
            $fields->add($fieldName, $fieldValueGetter($field));
        }

        return $fields;
    }

    public function getValuesToUnmap()
    {
        return $this->preferences->asCollection();
    }

    public function validateField(Collection $field)
    {
        (object) $fieldType = static::fields()->get($field->get('name'));

        if ($fieldType->anyValueIsAllowed()) {
            return $fieldType->isCorrectType($field->get('value'));
        } else {

            if (!$fieldType->isCorrectType($field->get('value'))) return false;

            if (is_array($field->get('value'))) {
                return $fieldType->getAllowedValues()->containAll($field->get('value'));;
            }

            return $fieldType->getAllowedValues()->contain($field->get('value'));
        }
    }
}