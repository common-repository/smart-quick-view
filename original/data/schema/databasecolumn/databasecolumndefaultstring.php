<?php

namespace SmartQuickView\Original\Data\Schema\DatabaseColumn;

use SmartQuickView\Original\Data\Schema\DatabaseColumn\DatabaseColumnDefault;

Class DatabaseColumnDefaultString extends DatabaseColumnDefault
{
    public function getDefinition()
    {
        return "DEFAULT '{$this->getCleanValue()}'";
    }
}
