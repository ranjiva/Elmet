<?php

namespace Elmet\AdminBundle\Entity;

class CurtainType
{
    protected $type;
    
    public function __construct($type)
    {
        $this->type = $type;
    }
    
    public function getType()
    {
        return $this->type;
    }
   
    public function setType($type)
    {
        $this->type = $type;
    }
}

?>
