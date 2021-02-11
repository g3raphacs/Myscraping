<?php

namespace App;

class Entity {
    
    public function hydrate($data)
    {
        $hydrateData = (array) $data;
        foreach ($hydrateData as $key => $value)
        {
            $method = 'set_'.$key;
            if(method_exists($this, $method))
            {
                $this->$method($value);
            }    
        }
    }
}