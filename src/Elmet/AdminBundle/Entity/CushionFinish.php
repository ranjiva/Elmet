<?php

namespace Elmet\AdminBundle\Entity;

class CushionFinish
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
