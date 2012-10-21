<?php

namespace Elmet\SiteBundle\Entity;
use Doctrine\ORM\mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="curtainpricebands")
*/
class CurtainPriceBand
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
     * @ORM\Column(type="string", length=25) 
     */
     protected $name;

     /**
      * @ORM\OneToMany(targetEntity="CurtainDesign", mappedBy="curtain_price_band")
      */
     protected $curtain_designs;

     /**
      * @ORM\OneToMany(targetEntity="CushionCover", mappedBy="curtain_price_band")
      */
     protected $cushion_covers;
     
     /**
      * @ORM\OneToMany(targetEntity="CurtainTieBack", mappedBy="curtain_price_band")
      */
     protected $curtain_tie_backs;
     
     /**
      * @ORM\OneToMany(targetEntity="CurtainPelmet", mappedBy="curtain_price_band")
      */
     protected $curtain_pelmets;
     
     /**
      * @ORM\OneToMany(targetEntity="CurtainFabric", mappedBy="curtain_price_band")
      */
     protected $curtain_fabrics;
     
     /**
      * @ORM\OneToMany(targetEntity="CurtainPrice", mappedBy="curtain_price_band")
      */
     protected $curtain_prices;
     
     public function __construct()
     {
          $this->curtain_designs = new ArrayCollection();
          $this->cushion_covers = new ArrayCollection();
          $this->curtain_tie_backs = new ArrayCollection();
          $this->curtain_pelmets = new ArrayCollection();
          $this->curtain_fabrics = new ArrayCollection();
          $this->curtain_prices = new ArrayCollection();
     }
     
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add curtain_designs
     *
     * @param Elmet\SiteBundle\Entity\CurtainDesign $curtainDesigns
     */
    public function addCurtainDesign(\Elmet\SiteBundle\Entity\CurtainDesign $curtainDesigns)
    {
        $this->curtain_designs[] = $curtainDesigns;
    }

    /**
     * Get curtain_designs
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCurtainDesigns()
    {
        return $this->curtain_designs;
    }

    /**
     * Add cushion_covers
     *
     * @param Elmet\SiteBundle\Entity\CushionCover $cushionCovers
     */
    public function addCushionCover(\Elmet\SiteBundle\Entity\CushionCover $cushionCovers)
    {
        $this->cushion_covers[] = $cushionCovers;
    }

    /**
     * Get cushion_covers
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCushionCovers()
    {
        return $this->cushion_covers;
    }

    /**
     * Add curtain_tie_backs
     *
     * @param Elmet\SiteBundle\Entity\CurtainTieBack $curtainTieBacks
     */
    public function addCurtainTieBack(\Elmet\SiteBundle\Entity\CurtainTieBack $curtainTieBacks)
    {
        $this->curtain_tie_backs[] = $curtainTieBacks;
    }

    /**
     * Get curtain_tie_backs
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCurtainTieBacks()
    {
        return $this->curtain_tie_backs;
    }

    /**
     * Add curtain_pelmets
     *
     * @param Elmet\SiteBundle\Entity\CurtainPelmet $curtainPelmets
     */
    public function addCurtainPelmet(\Elmet\SiteBundle\Entity\CurtainPelmet $curtainPelmets)
    {
        $this->curtain_pelmets[] = $curtainPelmets;
    }

    /**
     * Get curtain_pelmets
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCurtainPelmets()
    {
        return $this->curtain_pelmets;
    }

    /**
     * Add curtain_fabrics
     *
     * @param Elmet\SiteBundle\Entity\CurtainFabric $curtainFabrics
     */
    public function addCurtainFabric(\Elmet\SiteBundle\Entity\CurtainFabric $curtainFabrics)
    {
        $this->curtain_fabrics[] = $curtainFabrics;
    }

    /**
     * Get curtain_fabrics
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCurtainFabrics()
    {
        return $this->curtain_fabrics;
    }

    /**
     * Add curtain_prices
     *
     * @param Elmet\SiteBundle\Entity\CurtainPrice $curtainPrices
     */
    public function addCurtainPrice(\Elmet\SiteBundle\Entity\CurtainPrice $curtainPrices)
    {
        $this->curtain_prices[] = $curtainPrices;
    }

    /**
     * Get curtain_prices
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCurtainPrices()
    {
        return $this->curtain_prices;
    }
}