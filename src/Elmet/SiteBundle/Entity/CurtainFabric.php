<?php

namespace Elmet\SiteBundle\Entity;
use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="curtainfabrics")
*/
class CurtainFabric
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
      * @ORM\ManyToOne(targetEntity="CurtainPriceBand", inversedBy="curtain_fabrics")
      * @ORM\JoinColumn(name="curtainpriceband_id", referencedColumnName="id")
      */
     protected $curtain_price_band;
     
      /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
     protected $price_per_metre;
    


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
     * Set price_per_metre
     *
     * @param decimal $pricePerMetre
     */
    public function setPricePerMetre($pricePerMetre)
    {
        $this->price_per_metre = $pricePerMetre;
    }

    /**
     * Get price_per_metre
     *
     * @return decimal 
     */
    public function getPricePerMetre()
    {
        return $this->price_per_metre;
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