<?php

namespace SmartQuickView\Original\Utilities;

Trait ClassName
{

    public static function className() 
    {
        return get_called_class();
    }

    public function singleClassName()
    {
        (string) $fullyQualifiedClassName = get_class($this);
        (array) $classNamespacesAndName = explode('\\', $fullyQualifiedClassName);
        (string) $singleClassName = $classNamespacesAndName[count($classNamespacesAndName) - 1];

        return $singleClassName;
    }

    public function fullyQualifiedClassName()
    {
        return get_class($this);
    }
}