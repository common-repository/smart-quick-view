<?php

namespace SmartQuickView\Original\Collections\Mapper\Types;

use SmartQuickView\Original\Characters\StringManager;
use SmartQuickView\Original\Collections\Mapper\Types;

Class AnyType extends Types
{
    protected function setType()
    {
        return static::ANY;
    }

    public function isCorrectType($value)
    {
        return true;
    }

    public function hasDefaultValue()
    {
        return false;
    }

    public function concretePickValue($newValue)
    {
        return $newValue;
    }
}