<?php
declare(strict_types = 1);

namespace BxF;

trait PropertyAccess
{
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call(string $name, array $arguments)
    {
        $prefix = substr($name, 0, 3);
        $property = lcfirst(substr($name, 3));
        
        if(property_exists($this, $property))
        {
            if($prefix == 'set' && count($arguments) == 1)
            {
                if(method_exists($this, 'setModifiedIfChanged'))
                    $this->setModifiedIfChanged($property, $arguments[0]);
                
                $this->$property = $arguments[0];
                return $this;
            }
            elseif($prefix == 'get' && count($arguments) == 0)
                return $this->$property;
        }
        
        throw new \Exception('Call to undefined method '.get_called_class().'::'.$name.'()');
    }
}
