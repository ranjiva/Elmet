<?php

namespace Elmet\SiteBundle\Entity;

use Doctrine\ORM\mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="curtaindesigns")
*/
class CurtainDesign
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
     * @ORM\Column(type="string", length=100) 
     */
     protected $url_name;
     
     /**
     * @ORM\Column(type="string", length=55) 
     */
     protected $name;
     
     /**
     * @ORM\Column(type="text") 
     */
     protected $materials;
     
     /**
     * @ORM\Column(type="string", length=5) 
     */
     protected $tape_size;
     
     /**
     * @ORM\Column(type="string", length=1)
     */
     protected $lined;
     
     /**
     * @ORM\Column(type="smallint")
     */
     protected $eyelets_available;
     
     /**
     * @ORM\Column(type="smallint")
     */
     protected $fabric_width;
     
     /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
     protected $pattern_repeat_length;
     
     /**
     * @ORM\Column(type="string", length=50) 
     */
     protected $finish;
     
     /**
     * @ORM\Column(type="string", length=50) 
     */
     protected $cushion_finish;
     
     /**
     * @ORM\Column(type="string", length=1)
     */
     protected $new;
     
     /**
      * @ORM\ManyToOne(targetEntity="CurtainPriceBand", inversedBy="curtain_designs")
      * @ORM\JoinColumn(name="CurtainPriceband_id", referencedColumnName="id")
      */
     protected $curtain_price_band;
     
     /**
      * @ORM\OneToMany(targetEntity="CurtainColour", mappedBy="curtain_design", cascade={"persist", "merge", "detach", "remove"})
      */
     protected $curtain_colours;

     public function __construct()
     {
          $this->curtain_colours = new ArrayCollection();
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
     * Set url_name
     *
     * @param string $urlName
     */
    public function setUrlName($urlName)
    {
        $this->url_name = $urlName;
    }

    /**
     * Get url_name
     *
     * @return string 
     */
    public function getUrlName()
    {
        return $this->url_name;
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
     * Set materials
     *
     * @param text $materials
     */
    public function setMaterials($materials)
    {
        $this->materials = $materials;
    }

    /**
     * Get materials
     *
     * @return text 
     */
    public function getMaterials()
    {
        return $this->materials;
    }

    /**
     * Set tape_size
     *
     * @param string $tapeSize
     */
    public function setTapeSize($tapeSize)
    {
        $this->tape_size = $tapeSize;
    }

    /**
     * Get tape_size
     *
     * @return string 
     */
    public function getTapeSize()
    {
        return $this->tape_size;
    }

    /**
     * Set lined
     *
     * @param string $lined
     */
    public function setLined($lined)
    {
        $this->lined = $lined;
    }

    /**
     * Get lined
     *
     * @return string 
     */
    public function getLined()
    {
        return $this->lined;
    }

    /**
     * Set eyelets_available
     *
     * @param smallint $eyeletsAvailable
     */
    public function setEyeletsAvailable($eyeletsAvailable)
    {
        $this->eyelets_available = $eyeletsAvailable;
    }

    /**
     * Get eyelets_available
     *
     * @return smallint 
     */
    public function getEyeletsAvailable()
    {
        return $this->eyelets_available;
    }

    /**
     * Set fabric_width
     *
     * @param smallint $fabricWidth
     */
    public function setFabricWidth($fabricWidth)
    {
        $this->fabric_width = $fabricWidth;
    }

    /**
     * Get fabric_width
     *
     * @return smallint 
     */
    public function getFabricWidth()
    {
        return $this->fabric_width;
    }

    /**
     * Set pattern_repeat_length
     *
     * @param decimal $patternRepeatLength
     */
    public function setPatternRepeatLength($patternRepeatLength)
    {
        $this->pattern_repeat_length = $patternRepeatLength;
    }

    /**
     * Get pattern_repeat_length
     *
     * @return decimal 
     */
    public function getPatternRepeatLength()
    {
        return $this->pattern_repeat_length;
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
     * Set cushion_finish
     *
     * @param string $cushionFinish
     */
    public function setCushionFinish($cushionFinish)
    {
        $this->cushion_finish = $cushionFinish;
    }

    /**
     * Get cushion_finish
     *
     * @return string 
     */
    public function getCushionFinish()
    {
        return $this->cushion_finish;
    }

    /**
     * Set new
     *
     * @param string $new
     */
    public function setNew($new)
    {
        $this->new = $new;
    }

    /**
     * Get new
     *
     * @return string 
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * Get curtain_colours
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCurtainColours()
    {
        return $this->curtain_colours;
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
     * Add curtain_colours
     *
     * @param Elmet\SiteBundle\Entity\CurtainColour $curtainColours
     */
    public function addCurtainColour(\Elmet\SiteBundle\Entity\CurtainColour $curtainColours)
    {
        $this->curtain_colours[] = $curtainColours;
    }
    
    public function getSortedCurtainColoursById() {
        
        $colours = $this->curtain_colours->toArray();
        
        uasort($colours,array($this, 'comparatorId'));
        
        return $colours;
    }
    
    public function getSortedCurtainColoursByIdInStock() {
        
        $colours = $this->curtain_colours->toArray();
        
        uasort($colours,array($this, 'comparatorInStockAndId'));
        
        return $colours;
    }
    
    public function getCurtainColoursOnOffer() {
        
        $allColours = $this->curtain_colours->toArray();
        $colours = array();
        
        foreach($allColours as $colour) {
            if (($colour->getOnOffer() == 1) && ($colour->getInStock() == 1)) {
                $colours[] = $colour;
            }
        }
        
        return $colours;
    }
     
    public function getMaxDiscount() {
        
        $allColours = $this->curtain_colours->toArray();
        $max = -1000;
        
        foreach($allColours as $colour) {
            if (($colour->getOnOffer() == 1) && ($colour->getInStock() == 1)) {
                
                if($colour->getDiscountPercentage() > $max) {
                    $max = $colour->getDiscountPercentage();
                }
                
            }
        }
        
        return $max;
        
    }
    /*
     * sort in order of ascending id only
     */
    public function comparatorId($a,$b) {
        
        if ($a->getId() == $b->getId())
            return 0;
        else if ( $a->getId() < $b->getId() )         
            return -1;
        else 
            return 1; 
    }
    
    /*
     * sort in order of descending inStock and then ascending id 
     */
    public function comparatorInStockAndId($a,$b) {
        
        if ($a->getId() == $b->getId())
            return 0;
        else if ($a->getInStock() > $b->getInStock())
            return -1;
        else if ($a->getInStock() < $b->getInStock())
            return 1;
        else if ($a->getInStock() == $b->getInStock())
            if ( $a->getId() < $b->getId() )         
                return -1;
            else 
                return 1; 
    }
}