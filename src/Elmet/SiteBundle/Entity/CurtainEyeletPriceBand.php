<?php

namespace Elmet\SiteBundle\Entity;
use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="curtaineyeletpricebands")
*/
class CurtainEyeletPriceBand
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
     * @ORM\Column(type="string", length=10) 
     */
     protected $price;

     /**
     * @ORM\Column(type="string", length=20) 
     */
     protected $curtain_size;
     
     

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
     * Set price
     *
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set curtain_size
     *
     * @param string $curtainSize
     */
    public function setCurtainSize($curtainSize)
    {
        $this->curtain_size = $curtainSize;
    }

    /**
     * Get curtain_size
     *
     * @return string 
     */
    public function getCurtainSize()
    {
        return $this->curtain_size;
    }
}