<?php

namespace Elmet\AdminBundle\Entity;

class CurtainWidth
{
    protected $size;
    
    public function __construct($size)
    {
        $this->size = $size;
    }
    
    public function getSize()
    {
        return $this->size;
    }
   
    public function setSize($size)
    {
        $this->size = $size;
    }
}

?>
