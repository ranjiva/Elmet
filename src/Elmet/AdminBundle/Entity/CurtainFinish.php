<?php

namespace Elmet\AdminBundle\Entity;

class CurtainFinish
{
    protected $finish;
    
    public function __construct($finish)
    {
        $this->finish = $finish;
    }
    
    public function getFinish()
    {
        return $this->finish;
    }
    
    public function setFinish($finish)
    {
        $this->finish = $finish;
    }
     
}

?>
