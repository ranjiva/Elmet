<?php

namespace Elmet\SiteBundle\Entity;

use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity(repositoryClass="Elmet\SiteBundle\Repository\CurtainColourRepository")
* @ORM\Table(name="curtaincolours")
*/
class CurtainColour
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
      * @ORM\ManyToOne(targetEntity="CurtainDesign", inversedBy="curtain_colours")
      * @ORM\JoinColumn(name="curtaindesign_id", referencedColumnName="id")
      */
     protected $curtain_design;
     
     /**
     * @ORM\Column(type="string", length=55) 
     */
     protected $name;
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $full_filepath;
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $thumbnail_filepath;
     
     /**
     * @ORM\Column(type="string", length="255")
     */
     protected $swatch_filepath;
     
     /**
     * @ORM\Column(type="smallint")
     */
     protected $buynow;
     
     /**
     * @ORM\Column(type="smallint")
     */
     protected $in_stock;

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
     * Set full_filepath
     *
     * @param string $fullFilepath
     */
    public function setFullFilepath($fullFilepath)
    {
        $this->full_filepath = $fullFilepath;
    }

    /**
     * Get full_filepath
     *
     * @return string 
     */
    public function getFullFilepath()
    {
        return $this->full_filepath;
    }

    /**
     * Set thumbnail_filepath
     *
     * @param string $thumbnailFilepath
     */
    public function setThumbnailFilepath($thumbnailFilepath)
    {
        $this->thumbnail_filepath = $thumbnailFilepath;
    }

    /**
     * Get thumbnail_filepath
     *
     * @return string 
     */
    public function getThumbnailFilepath()
    {
        return $this->thumbnail_filepath;
    }

    /**
     * Set swatch_filepath
     *
     * @param string $swatchFilepath
     */
    public function setSwatchFilepath($swatchFilepath)
    {
        $this->swatch_filepath = $swatchFilepath;
    }

    /**
     * Get swatch_filepath
     *
     * @return string 
     */
    public function getSwatchFilepath()
    {
        return $this->swatch_filepath;
    }

    /**
     * Set buynow
     *
     * @param smallint $buynow
     */
    public function setBuynow($buynow)
    {
        $this->buynow = $buynow;
    }

    /**
     * Get buynow
     *
     * @return smallint 
     */
    public function getBuynow()
    {
        return $this->buynow;
    }

    /**
     * Set in_stock
     *
     * @param smallint $inStock
     */
    public function setInStock($inStock)
    {
        $this->in_stock = $inStock;
    }

    /**
     * Get in_stock
     *
     * @return smallint 
     */
    public function getInStock()
    {
        return $this->in_stock;
    }

    /**
     * Set curtain_design
     *
     * @param Elmet\SiteBundle\Entity\CurtainDesign $curtainDesign
     */
    public function setCurtainDesign(\Elmet\SiteBundle\Entity\CurtainDesign $curtainDesign)
    {
        $this->curtain_design = $curtainDesign;
    }

    /**
     * Get curtain_design
     *
     * @return Elmet\SiteBundle\Entity\CurtainDesign 
     */
    public function getCurtainDesign()
    {
        return $this->curtain_design;
    }
}