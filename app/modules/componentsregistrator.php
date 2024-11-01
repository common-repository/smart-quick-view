<?php

namespace SmartQuickView\App\Modules;

use SmartQuickView\Original\Collections\Collection;
use SmartQuickView\Original\Collections\JSONMapper;
use SmartQuickView\Original\Collections\Mapper\Types;
use SmartQuickView\Original\Environment\Env;
use SmartQuickView\Original\Utilities\TypeChecker;

Abstract Class ComponentsRegistrator
{
    use TypeChecker;

    #protected static $instance;

    protected $components = []; 

    abstract static protected function getComponentId() : string;
    abstract static protected function getComponentType() : string;

    public static function get()
    {
        if (!static::$instance) {
            static::$instance = new static;
        }

        return static::$instance;   
    }

    public function all()
    {
        return new Collection($this->components);   
    }
    
    protected function __construct()
    {
        do_action(Env::idLowerCase(). '_register_'.static::getComponentId().'_component', $this);
    }

    public function register(string $Component)
    {
        $this->components[$Component::TYPE] = $this->expect($Component)
                                           ->toBe(static::getComponentType());
    }
}