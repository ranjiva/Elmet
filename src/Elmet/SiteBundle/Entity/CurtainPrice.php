<?php

namespace Elmet\SiteBundle\Entity;
use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="curtainprices")
*/
class CurtainPrice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
     * @ORM\ManyToOne(targetEntity="CurtainPriceBand", inversedBy="curtain_prices")
     * @ORM\JoinColumn(name="curtainpriceband_id", referencedColumnName="id")
     */
     protected $curtain_price_band;
     
     /**
     * @ORM\OneToOne(targetEntity="CurtainEyeletPriceBand")
     * @ORM\JoinColumn(name="curtain_eyelet_priceband_id", referencedColumnName="id")
     */
     protected $curtain_eyelet_price_band;
     
     /**
     * @ORM\Column(type="string", length=20) 
     */
     protected $size;
     
     /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
     protected $price;
     
     /**
     * @ORM\Column(type="decimal", precision=9, scale=2)
     */
     protected $width;
     
     /**
     * @ORM\Column(type="decimal", precision=9, scale=2)
     */
     protected $height;
     
     /**
     * @ORM\Column(type="string", length=25) 
     */
     protected $type;
     

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
     * Set width
     *
     * @param decimal $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get width
     *
     * @return decimal 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param decimal $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get height
     *
     * @return decimal 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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

    /**
     * Set curtain_eyelet_price_band
     *
     * @param Elmet\SiteBundle\Entity\CurtainEyeletPriceBand $curtainEyeletPriceBand
     */
    public function setCurtainEyeletPriceBand(\Elmet\SiteBundle\Entity\CurtainEyeletPriceBand $curtainEyeletPriceBand)
    {
        $this->curtain_eyelet_price_band = $curtainEyeletPriceBand;
    }

    /**
     * Get curtain_eyelet_price_band
     *
     * @return Elmet\SiteBundle\Entity\CurtainEyeletPriceBand 
     */
    public function getCurtainEyeletPriceBand()
    {
        return $this->curtain_eyelet_price_band;
    }
}