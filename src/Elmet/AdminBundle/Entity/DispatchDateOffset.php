<?php

namespace Elmet\AdminBundle\Entity;

use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="dispatch_date_offset")
*/
class DispatchDateOffset
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
     * @ORM\Column(type="integer", length=1) 
     */
     protected $day_of_week;
     
     /**
     * @ORM\Column(type="integer", length=2) 
     */
     protected $offset;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set day_of_week
     *
     * @param integer $dayOfWeek
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->day_of_week = $dayOfWeek;
    }

    /**
     * Get day_of_week
     *
     * @return integer 
     */
    public function getDayOfWeek()
    {
        return $this->day_of_week;
    }

    /**
     * Set offset
     *
     * @param integer $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * Get offset
     *
     * @return integer 
     */
    public function getOffset()
    {
        return $this->offset;
    }
    
    public function getDispatchDate() {
        
        $dispatchDate = strtotime("+".$this->offset." day");
        
        $dateParts = explode("/",date('d/m/y',$dispatchDate));
        
        return date_create($dateParts[2]."-".$dateParts[1]."-".$dateParts[0]);
    }
}