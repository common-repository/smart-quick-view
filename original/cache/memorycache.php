<?php

namespace SmartQuickView\Original\Cache;

use SmartQuickView\Original\Cache\Cache;

Class MemoryCache extends Cache
{
    public function get($key)
    {
        return $this->data->get($key);
    }
    
    public function getIfExists($key) 
    {
        return new ExistingValueResolver([
            'key' => $key,
            'data' => $this->data,
        ]);
    } 
}