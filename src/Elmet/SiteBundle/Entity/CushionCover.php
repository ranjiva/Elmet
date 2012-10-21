<?php

namespace Elmet\SiteBundle\Entity;
use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="cushioncovers")
*/
class CushionCover
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
      * @ORM\ManyToOne(targetEntity="CurtainPriceBand", inversedBy="cushion_covers")
      * @ORM\JoinColumn(name="curtainpriceband_id", referencedColumnName="id")
      */
     protected $curtain_price_band;
     
     /**
     * @ORM\Column(type="string", length=50) 
     */
     protected $size;
     
     /**
     * @ORM\Column(type="string", length=100) 
     */
     protected $finish;
     
      /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
     protected $price;
     

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
     * Set finish
     *
     * @param string $finish
     */
    public function setFinish($finish)
    {
        $this->finish = $finish;
    }

    /**
     * Get finish
     *
     * @return string 
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return decimal 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set curtain_price_band
     *
     * @param Elmet\SiteBundle\Entity\CurtainPriceBand $curtainPriceBand
     */
    public function setCurtainPriceBand(\Elmet\SiteBundle\Entity\CurtainPriceBand $curtainPriceBand)
    {
        $this->curtain_price_band = $curtainPriceBand;
    }

    /**
     * Get curtain_price_band
     *
     * @return Elmet\SiteBundle\Entity\CurtainPriceBand 
     */
    public function getCurtainPriceBand()
    {
        return $this->curtain_price_band;
    }
}