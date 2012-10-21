<?php

namespace Elmet\AdminBundle\Entity;

class CurtainSize
{
    protected $size;
    protected $width;
    protected $height;
    
    public function __construct($size,$width,$height)
    {
        $this->size = $size;
        $this->width = $width;
        $this->width = $height;
    }
    
    public function getSize()
    {
        return $this->size;
    }
    
    public function getWidth()
    {
        return $this->width;
    }
    
    public function getHeight()
    {
        return $this->height;
    }
    
   
    public function setSize($size)
    {
        $this->size = $size;
    }
    
    public function setWidth($width)
    {
        $this->width = $width;
    }
    
    public function setHeight($height)
    {
        $this->height = $height;
    }
    
}

?>
