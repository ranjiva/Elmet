<?php

namespace Elmet\SiteBundle\Entity;
use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="curtain_meterage")
*/
class CurtainMeterage
{
   /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
     * @ORM\Column(type="string", length=20) 
     */
     protected $size;
     
     /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
     protected $meterage;

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
     * Set size
     *
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set meterage
     *
     * @param decimal $meterage
     */
    public function setMeterage($meterage)
    {
        $this->meterage = $meterage;
    }

    /**
     * Get meterage
     *
     * @return decimal 
     */
    public function getMeterage()
    {
        return $this->meterage;
    }
}